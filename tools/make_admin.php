<?php
declare(strict_types=1);

/**
 * Crée ou remet à zéro un compte du back-office.
 *
 *     php tools/make_admin.php <identifiant>
 *
 * Le mot de passe est demandé à l'écran (jamais en argument : la ligne de
 * commande reste dans l'historique du shell et dans la liste des processus).
 * Seul son hash est enregistré.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require dirname(__DIR__) . '/api/bootstrap.php';

$username = trim((string) ($argv[1] ?? ''));
if ($username === '' || !preg_match('/^[A-Za-z0-9._-]{3,80}$/', $username)) {
    fwrite(STDERR, "Usage : php tools/make_admin.php <identifiant>\n"
                 . "        (3 à 80 caractères : lettres, chiffres, . _ -)\n");
    exit(1);
}

/** Saisie sans écho quand le terminal le permet. */
function prompt_secret(string $label): string
{
    fwrite(STDOUT, $label);
    $hidden = stripos(PHP_OS, 'WIN') !== 0 && @shell_exec('command -v stty') !== null;
    if ($hidden) {
        shell_exec('stty -echo');
    }
    $value = rtrim((string) fgets(STDIN), "\r\n");
    if ($hidden) {
        shell_exec('stty echo');
        fwrite(STDOUT, "\n");
    }
    return $value;
}

$password = prompt_secret("Mot de passe : ");
$confirm  = prompt_secret("Confirmation : ");

if ($password !== $confirm) {
    fwrite(STDERR, "Les deux saisies diffèrent.\n");
    exit(1);
}
if (strlen($password) < 12) {
    fwrite(STDERR, "Mot de passe trop court : 12 caractères minimum.\n");
    exit(1);
}
// Un mot de passe qui n'est qu'un mot du dictionnaire ou une suite de chiffres
// tombe en quelques minutes. On refuse les cas les plus évidents.
if (preg_match('/^(?:[a-z]+|[0-9]+)$/i', $password)) {
    fwrite(STDERR, "Mot de passe trop simple : mélangez lettres, chiffres et symboles.\n");
    exit(1);
}

$pdo  = db();
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('SELECT id FROM admin_users WHERE username = ?');
$stmt->execute([$username]);

if ($stmt->fetch()) {
    $pdo->prepare('UPDATE admin_users SET password_hash = ?, failed_count = 0,
                          locked_until = NULL WHERE username = ?')
        ->execute([$hash, $username]);
    fwrite(STDOUT, "Mot de passe mis à jour pour « $username ».\n");
} else {
    $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)')
        ->execute([$username, $hash]);
    fwrite(STDOUT, "Compte « $username » créé.\n");
}

fwrite(STDOUT, "Connexion : https://votre-domaine/admin/\n");
