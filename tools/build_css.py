#!/usr/bin/env python3
"""
Assemble assets/css/main.min.css from the original theme's stylesheets.

Concatenation order mirrors exactly what the `constrau` theme enqueued in
wp-content/themes/constrau/extend/add_js_css.php, so the cascade is unchanged.
On top of that it appends:

  * the CSS the theme's own customizer generated (rendered by
    tools/render_customizer.php through a stub of the WordPress API), and
  * tools/data/site-extra.css, which styles the components that replaced the
    jQuery plugin stack (slider, lightbox, gallery, footer, forms).

Legacy .eot/.svg @font-face sources are dropped - every browser that can run
this site takes woff2/woff/ttf.

Usage:  python3 tools/build_css.py [--theme PATH]
"""

import argparse
import glob
import os
import re
import shutil
import subprocess
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA = os.path.join(ROOT, "tools", "data")
FONTS_OUT = os.path.join(ROOT, "assets", "fonts")
CSS_OUT = os.path.join(ROOT, "assets", "css", "main.min.css")

FLATICON_SETS = [
    "arrows", "construction", "construction-icons", "construction-industry",
    "construction-line-craft", "contact", "labor-day", "motivation",
    "pointers", "productivity-icons",
]

DROP_FONT_EXT = (".eot", ".svg")


def copy_fonts(srcdir, patterns, prefix=""):
    if not os.path.isdir(srcdir):
        return
    for pat in patterns:
        for f in glob.glob(os.path.join(srcdir, pat)):
            shutil.copy2(f, os.path.join(FONTS_OUT, prefix + os.path.basename(f)))


def sanitize_font_faces(css):
    """Rewrite every @font-face so it lists only modern, existing sources."""
    rank = {"woff2": 0, "woff": 1, "truetype": 2, "opentype": 3}

    def fix(m):
        block = m.group(0)
        inner = block[block.index("{") + 1: block.rindex("}")]

        entries, seen = [], set()
        for decl in re.findall(r"src\s*:\s*([^;}]*)", inner):
            for part in re.split(r",(?![^(]*\))", decl):
                part = part.strip().rstrip(",")
                url = re.search(r"url\(\s*[\"']?([^\"')]+)", part)
                if not part or not url:
                    continue
                clean = url.group(1).split("?")[0].split("#")[0]
                if clean.lower().endswith(DROP_FONT_EXT) or clean in seen:
                    continue
                seen.add(clean)
                entries.append(part)

        # Everything that is not an src: declaration.
        others = []
        for decl in re.split(r";", inner):
            decl = decl.strip()
            if not decl or re.match(r"src\s*:", decl):
                continue
            others.append("  " + decl + ";")

        if not entries:
            return ""  # nothing loadable left - drop the block entirely

        def key(e):
            f = re.search(r"format\(\s*[\"']?([^\"')]+)", e)
            return rank.get(f.group(1) if f else "", 9)

        entries.sort(key=key)
        src = "  src: " + ",\n       ".join(entries) + ";"
        return "@font-face {\n" + src + "\n" + "\n".join(others) + "\n}"

    css = re.sub(r"@font-face\s*\{[^}]*\}", fix, css)
    # Tidy any @media wrapper left holding nothing.
    css = re.sub(r"@media[^{]*\{\s*\}", "", css)
    return css


def build_bundle(theme):
    os.makedirs(FONTS_OUT, exist_ok=True)
    os.makedirs(os.path.dirname(CSS_OUT), exist_ok=True)
    parts = []

    def add(label, path, transform=None):
        css = open(path, encoding="utf-8").read()
        if transform:
            css = transform(css)
        parts.append("/* ---- %s ---- */\n%s\n" % (label, css))

    # Self-hosted Lato + Rajdhani (the theme's declared font pair).
    add("google-fonts", os.path.join(DATA, "fonts-local.css"))

    # 1. Bootstrap
    add("bootstrap", os.path.join(theme, "assets/libs/bootstrap/css/bootstrap.min.css"))

    # 2. Flaticon icon sets
    for s in FLATICON_SETS:
        d = os.path.join(theme, "assets/libs/flaticon", s)
        copy_fonts(d, ["Flaticon.woff", "Flaticon.ttf"], prefix="flaticon-%s-" % s)
        add("flaticon-%s" % s, os.path.join(d, "flaticon.css"),
            lambda c, s=s: c.replace("./Flaticon.", "../fonts/flaticon-%s-Flaticon." % s))

    # 3. Select2
    add("select2", os.path.join(theme, "assets/libs/select2/select2.min.css"))

    # 4/5. Font Awesome
    copy_fonts(os.path.join(theme, "assets/libs/fontawesome/webfonts"),
               ["*.woff2", "*.woff", "*.ttf"])
    for name in ["v4-shims.min.css", "all.min.css"]:
        add(name, os.path.join(theme, "assets/libs/fontawesome/css", name),
            lambda c: c.replace("../webfonts/", "../fonts/"))

    # 6. Elegant icons
    d = os.path.join(theme, "assets/libs/elegant_font")
    copy_fonts(os.path.join(d, "fonts"), ["ElegantIcons.woff", "ElegantIcons.ttf"])
    add("elegant", os.path.join(d, "ele_style.css"),
        lambda c: c.replace("fonts/ElegantIcons.", "../fonts/ElegantIcons."))

    # 7. Material Design Iconic Font
    d = os.path.join(theme, "assets/libs/material-design-iconic-font")
    copy_fonts(os.path.join(d, "fonts"), ["*.woff2", "*.woff", "*.ttf"])
    add("material", os.path.join(d, "css/material-design-iconic-font.min.css"))

    # 8. Theme stylesheet
    add("theme", os.path.join(theme, "assets/css/theme.css"))

    # 9. Theme customizer output
    customizer = os.path.join(DATA, "customizer.css")
    if os.path.exists(customizer):
        add("customizer", customizer)

    # 10. Static-site additions
    add("site-extra", os.path.join(DATA, "site-extra.css"))

    return sanitize_font_faces("\n".join(parts))


def run_node(script, *args):
    r = subprocess.run(["node", os.path.join(DATA, script)] + list(args),
                       capture_output=True, text=True, cwd=ROOT)
    if r.returncode != 0:
        sys.stderr.write(r.stdout + r.stderr)
        sys.exit("node %s failed" % script)
    return r.stdout.strip()


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--theme", default=os.path.join(DATA, "theme-src"),
                    help="path to the constrau theme directory")
    ap.add_argument("--no-purge", action="store_true")
    args = ap.parse_args()

    if not os.path.isdir(args.theme):
        sys.exit("theme sources not found at %s" % args.theme)

    bundle = build_bundle(args.theme)
    raw = os.path.join(DATA, ".bundle.css")
    open(raw, "w", encoding="utf-8").write(bundle)
    print("bundle: %d bytes, %d @font-face blocks" % (len(bundle), bundle.count("@font-face")))

    src = raw
    if not args.no_purge:
        purged = os.path.join(DATA, ".bundle.purged.css")
        print(run_node("purge.js", raw, purged))
        src = purged

    print(run_node("minify.js", src, CSS_OUT))
    for f in (raw, os.path.join(DATA, ".bundle.purged.css")):
        if os.path.exists(f):
            os.remove(f)


if __name__ == "__main__":
    main()
