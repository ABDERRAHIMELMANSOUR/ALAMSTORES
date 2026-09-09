#!/usr/bin/env python3
"""Scanner anti-webshell pour le site déployé.

À lancer sur le serveur, régulièrement (cron hebdomadaire) et après chaque
publication. Il cherche ce qui avait effectivement compromis l'ancien site
WordPress — voir SECURITY-AUDIT.md :

  * un fichier PHP là où il ne devrait pas y en avoir (assets/, uploads/, la
    racine) : c'est le scénario du webshell téléversé ;
  * les signatures classiques de porte dérobée (eval sur des données entrantes,
    chaînes base64/gzinflate, décodeurs parse_str, balises `<?phP` en casse
    mélangée pour tromper les greps) ;
  * toute modification d'un fichier PHP légitime, détectée par empreinte.

Usage
-----
    python3 tools/security_scan.py                # analyse
    python3 tools/security_scan.py --baseline     # (re)crée l'empreinte de référence
    python3 tools/security_scan.py --quiet        # ne parle qu'en cas de problème

Code de sortie : 0 si tout est propre, 1 si quelque chose doit être regardé.
Convient donc tel quel à une tâche cron qui n'envoie un mail qu'en cas d'échec.
"""

import argparse
import hashlib
import io
import json
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
MANIFEST = os.path.join(ROOT, "tools", "data", "php-manifest.json")

# Les seuls dossiers où du PHP a le droit d'exister, en plus des pages du site
# à la racine. Tout le reste — assets/uploads/ en tête — est suspect.
PHP_ALLOWED = ("api/", "admin/", "includes/", "tools/")

SKIP_DIRS = {".git", "node_modules", ".idea", ".vscode"}

CODE_EXT = (".php", ".phtml", ".php3", ".php4", ".php5", ".php7", ".php8",
            ".phar", ".pht", ".cgi", ".pl", ".py", ".sh", ".asp", ".aspx", ".jsp")

# Signatures. Chaque entrée : (identifiant, expression, gravité, explication[, flags]).
# Par défaut la recherche ignore la casse ; une signature qui a justement besoin
# de la distinguer passe ses propres flags.
SIGNATURES = [
    ("eval-entrant",
     rb"eval\s*\(\s*(?:stripslashes\s*\(\s*)?\$_(?:GET|POST|REQUEST|COOKIE|SERVER)",
     "critique", "exécute directement ce que l'appelant envoie"),
    ("eval-encode",
     rb"eval\s*\(\s*(?:base64_decode|gzinflate|gzuncompress|str_rot13|strrev|convert_uu)",
     "critique", "code exécuté après décodage : porte dérobée obfusquée"),
    ("preg-replace-e",
     rb"preg_replace\s*\(\s*[\"'][^\"']*[\"']\s*\.?\s*[\"']?e[\"']?\s*[,)]",
     "critique", "modificateur /e : exécution de code par expression régulière"),
    ("assert-entrant",
     rb"assert\s*\(\s*\$_(?:GET|POST|REQUEST|COOKIE)",
     "critique", "assert() utilisé comme eval()"),
    ("shell-entrant",
     rb"(?:system|exec|passthru|shell_exec|popen|proc_open|pcntl_exec)\s*\(\s*\$_(?:GET|POST|REQUEST|COOKIE)",
     "critique", "commande système pilotée par la requête"),
    ("parse-str-decodeur",
     rb"parse_str\s*\(\s*(?:\$_SERVER\[|\$_(?:GET|POST|REQUEST)\[|[^,)]*base64)",
     "critique", "décodeur de charge utile typique des webshells trouvés en 2024"),
    # Sans IGNORECASE : c'est la casse elle-même qui trahit le fichier, une
    # recherche insensible à la casse exclurait aussi le `<?php` légitime.
    ("balise-casse-melangee",
     rb"<\?(?!php\b)[pP][hH][pP]\b",
     "critique", "balise PHP en casse mélangée : écrite pour échapper aux recherches", 0),
    ("fonction-variable",
     rb"\$(?:_(?:GET|POST|REQUEST|COOKIE)\[[^\]]+\]|[A-Za-z_]\w*)\s*\(\s*\$_(?:GET|POST|REQUEST)",
     "élevée", "appel de fonction dont le nom vient de la requête"),
    ("upload-direct",
     rb"move_uploaded_file\s*\(\s*\$_FILES\[[^\]]+\]\s*\[\s*[\"']tmp_name",
     "moyenne", "téléversement sans contrôle apparent (à vérifier)"),
    ("ecriture-php",
     rb"(?:file_put_contents|fwrite)\s*\([^)]*\.ph(?:p|tml)[\"']",
     "élevée", "écrit un fichier PHP : mécanisme de persistance"),
    ("creation-utilisateur",
     rb"(?:wp_create_user|wp_insert_user|wp_set_password)",
     "élevée", "création de compte : reliquat WordPress ou porte dérobée"),
    ("iframe-injectee",
     rb"<iframe[^>]+(?:display\s*:\s*none|width\s*=\s*[\"']?[01][\"']?)",
     "moyenne", "iframe invisible : injection publicitaire ou de malveillance"),
]

COMPILED = [(sig[0], re.compile(sig[1], sig[4] if len(sig) > 4 else re.I), sig[2], sig[3])
            for sig in SIGNATURES]

# Fichiers dont le contenu contient légitimement certains de ces motifs :
# ce scanner, et l'audit qui les documente.
SELF = {"tools/security_scan.py", "SECURITY-AUDIT.md"}


def walk():
    for base, dirs, files in os.walk(ROOT):
        dirs[:] = [d for d in dirs if d not in SKIP_DIRS]
        for name in files:
            full = os.path.join(base, name)
            yield full, os.path.relpath(full, ROOT).replace(os.sep, "/")


def sha256(path):
    h = hashlib.sha256()
    with open(path, "rb") as fh:
        for chunk in iter(lambda: fh.read(65536), b""):
            h.update(chunk)
    return h.hexdigest()


def build_baseline():
    manifest = {}
    for full, rel in walk():
        if rel.endswith(".php"):
            manifest[rel] = sha256(full)
    os.makedirs(os.path.dirname(MANIFEST), exist_ok=True)
    io.open(MANIFEST, "w", encoding="utf-8", newline="\n").write(
        json.dumps({"files": manifest}, indent=2, sort_keys=True) + "\n")
    print("Empreinte de référence écrite : %d fichiers PHP" % len(manifest))
    print("  -> %s" % os.path.relpath(MANIFEST, ROOT))


def load_baseline():
    if not os.path.exists(MANIFEST):
        return None
    try:
        return json.load(open(MANIFEST, encoding="utf-8")).get("files", {})
    except (ValueError, OSError):
        return None


def scan():
    findings = []          # (gravité, chemin, message)
    php_seen = {}

    for full, rel in walk():
        lower = rel.lower()

        # 1. du code exécutable là où il ne devrait pas y en avoir.
        # Les pages du site sont des .php à la racine : c'est le seul endroit
        # hors des dossiers autorisés où l'on en attend.
        at_root = "/" not in rel
        if lower.endswith(CODE_EXT) and not rel.startswith(PHP_ALLOWED) \
                and not (at_root and lower.endswith(".php")):
            findings.append(("critique", rel,
                             "fichier exécutable hors des dossiers autorisés "
                             "(racine, api/, admin/, includes/, tools/)"))

        if not lower.endswith((".php", ".phtml", ".html", ".htm", ".js", ".htaccess")) \
                and not lower.endswith(CODE_EXT):
            continue
        if rel in SELF:
            continue

        try:
            if os.path.getsize(full) > 4 * 1024 * 1024:
                continue                       # une image mal nommée, pas du code
            data = open(full, "rb").read()
        except OSError:
            continue

        if rel.endswith(".php"):
            php_seen[rel] = sha256(full)

        # 2. signatures de porte dérobée
        for name, regex, level, why in COMPILED:
            match = regex.search(data)
            if match:
                line = data[:match.start()].count(b"\n") + 1
                findings.append((level, "%s:%d" % (rel, line), "%s — %s" % (name, why)))

        # 3. charge utile encodée très longue (base64 de plusieurs kilo-octets)
        for blob in re.findall(rb"[\"'][A-Za-z0-9+/=]{800,}[\"']", data):
            findings.append(("élevée", rel,
                             "chaîne encodée de %d octets : charge utile probable"
                             % len(blob)))
            break

    # 4. l'installateur laissé en place
    if os.path.exists(os.path.join(ROOT, "admin", "setup.php")):
        findings.append(("élevée", "admin/setup.php",
                         "installateur encore présent : à supprimer une fois "
                         "le back-office installé"))

    # 5. dérive par rapport à l'empreinte de référence
    baseline = load_baseline()
    if baseline is None:
        findings.append(("info", "-",
                         "aucune empreinte de référence : lancez "
                         "`python3 tools/security_scan.py --baseline` sur une "
                         "installation saine"))
    else:
        for rel, digest in sorted(php_seen.items()):
            if rel not in baseline:
                findings.append(("critique", rel, "fichier PHP inconnu (ajouté après la référence)"))
            elif baseline[rel] != digest:
                findings.append(("élevée", rel, "fichier PHP modifié depuis la référence"))
        for rel in sorted(baseline):
            if rel not in php_seen and rel != "admin/setup.php":
                findings.append(("moyenne", rel, "fichier PHP attendu mais absent"))

    return findings


def main():
    parser = argparse.ArgumentParser(description=__doc__,
                                     formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--baseline", action="store_true",
                        help="enregistre l'empreinte des fichiers PHP actuels")
    parser.add_argument("--quiet", action="store_true",
                        help="n'affiche rien si tout est propre")
    args = parser.parse_args()

    if args.baseline:
        build_baseline()
        return 0

    findings = scan()
    order = {"critique": 0, "élevée": 1, "moyenne": 2, "info": 3}
    findings.sort(key=lambda f: (order.get(f[0], 9), f[1]))

    serious = [f for f in findings if f[0] in ("critique", "élevée")]

    if not findings:
        if not args.quiet:
            print("Analyse terminée : rien à signaler.")
        return 0

    if args.quiet and not serious:
        return 0

    print("Analyse de %s" % ROOT)
    print("%d point(s) à regarder :\n" % len(findings))
    for level, where, message in findings:
        print("  [%-8s] %-52s %s" % (level, where, message))

    if serious:
        print("\nÀ traiter en priorité : mettez le site hors ligne, comparez avec")
        print("le dépôt git, et ne restaurez que des fichiers vérifiés.")
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
