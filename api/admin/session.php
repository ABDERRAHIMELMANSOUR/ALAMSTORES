<?php
declare(strict_types=1);

/**
 * Connexion au back-office.
 *
 *   GET    api/admin/session.php   → l'utilisateur courant + le jeton CSRF
 *   POST   api/admin/session.php   → connexion {username, password}
 *   DELETE api/admin/session.php   → déconnexion
 *
 * Le mot de passe n'est jamais stocké : seul un hash password_hash() l'est.
 * Cinq échecs verrouillent le compte un quart d'heure.
 */

require dirname(__DIR__) . '/bootstrap.php';

$method = require_method('GET', 'POST', 'DELETE');
send_security_headers();
start_session();

// ------------------------------------------------------------------ lecture
if ($method === 'GET') {
    $user = current_user();
    json_out([
        'authenticated' => (bool) $user,
        'username'      => $user['username'] ?? null,
        'csrf'          => csrf_token(),
    ]);
}

// -------------------------------------------------------------- déconnexion
if ($method === 'DELETE') {
    require_csrf(json_body());
    logout();
    json_out(['ok' => true]);
}

// ----------------------------------------------------------------- connexion
$body     = json_body();
$username = clean_text($body['username'] ?? '', 80);
$password = (string) ($body['password'] ?? '');

if ($username === '' || $password === '') {
    json_out(['error' => 'Identifiant et mot de passe requis.'], 422);
}

$pdo  = db();
$stmt = $pdo->prepare('SELECT id, username, password_hash, failed_count, locked_until
                         FROM admin_users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

// Réponse identique que le compte existe ou non : rien ne permet d'énumérer
// les identifiants valides.
$generic = 'Identifiant ou mot de passe incorrect.';

if ($user && $user['locked_until'] !== null && strtotime((string) $user['locked_until']) > time()) {
    json_out(['error' => 'Compte temporairement bloqué après plusieurs échecs. Réessayez dans quelques minutes.'], 429);
}

// Hash factice quand le compte n'existe pas : la vérification prend le même
// temps dans les deux cas, ce qui ferme l'attaque par mesure de temps.
$hash = $user['password_hash'] ?? '$2y$12$YdMphhv9dvfVCebTi84wo.dOXq4DHMHQDwm236F8BaZ1oZx9cf/cW';

if (!password_verify($password, $hash) || !$user) {
    if ($user) {
        $tries = (int) $user['failed_count'] + 1;
        $lock  = $tries >= LOGIN_MAX_TRIES
            ? date('Y-m-d H:i:s', time() + LOGIN_LOCK_SECS)
            : null;
        $pdo->prepare('UPDATE admin_users SET failed_count = ?, locked_until = ? WHERE id = ?')
            ->execute([$tries >= LOGIN_MAX_TRIES ? 0 : $tries, $lock, $user['id']]);
    }
    usleep(random_int(150000, 400000));   // ralentit le bourrage d'identifiants
    json_out(['error' => $generic], 401);
}

// Le coût de hachage a pu changer depuis la création du compte.
if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
    $pdo->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
        ->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
}

$pdo->prepare('UPDATE admin_users SET failed_count = 0, locked_until = NULL,
                      last_login_at = CURRENT_TIMESTAMP WHERE id = ?')
    ->execute([$user['id']]);

// Identifiant de session neuf : une session préexistante ne peut pas être
// « fixée » puis récupérée une fois authentifiée.
session_regenerate_id(true);
$_SESSION['user']      = ['id' => (int) $user['id'], 'username' => $user['username']];
$_SESSION['last_seen'] = time();
unset($_SESSION['csrf']);

json_out(['ok' => true, 'username' => $user['username'], 'csrf' => csrf_token()]);
