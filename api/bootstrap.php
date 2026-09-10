<?php
declare(strict_types=1);

/**
 * Socle commun à toute l'API : configuration, connexion PDO, session,
 * en-têtes de sécurité, CSRF, helpers JSON.
 *
 * Chaque point d'entrée commence par `require __DIR__ . '/bootstrap.php';`.
 * Rien ici n'écrit dans la sortie : les endpoints gardent la main.
 */

if (PHP_VERSION_ID < 70400) {
    http_response_code(500);
    exit('PHP 7.4 ou supérieur est requis.');
}

// Ce fichier n'est pas un point d'entrée.
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'bootstrap.php') {
    http_response_code(404);
    exit;
}

const MAX_BODY_BYTES   = 262144;   // 256 Ko : largement au-dessus d'un formulaire
const SESSION_LIFETIME = 7200;     // 2 h d'inactivité avant déconnexion
const LOGIN_MAX_TRIES  = 5;
const LOGIN_LOCK_SECS  = 900;      // 15 min de blocage après 5 échecs

// ---------------------------------------------------------------- configuration

/**
 * Valeurs par défaut, utilisées tant que api/config.php n'existe pas.
 *
 * Elles ne contiennent aucun secret : pas d'accès base, pas de clé privée.
 * Le site reste donc utilisable dès le transfert FTP — les pages s'affichent,
 * le formulaire de devis fonctionne et part vers WhatsApp — et l'installation
 * ne fait qu'ajouter ce qui demande des identifiants.
 */
function config_defaults(): array
{
    return [
        'db'              => [],
        'ip_salt'         => '',
        'whatsapp'        => '212600055562',
        'notify_email'    => ['contact@alamstores.ma', 'kassettebrahim.1997@gmail.com'],
        'recaptcha'       => ['site_key' => '', 'secret_key' => '',
                              'version' => 'v2', 'min_score' => 0.5],
        'allowed_origins' => [],
        'debug'           => false,
    ];
}

/** Vrai quand api/config.php existe et se charge. */
function config_installed(): bool
{
    static $ok = null;
    if ($ok === null) {
        $path = __DIR__ . '/config.php';
        $ok = is_file($path) && is_array(@require $path);
    }
    return $ok;
}

/**
 * La configuration : les valeurs par défaut, écrasées par api/config.php quand
 * il est là. Ne s'interrompt jamais — un fichier manquant est un site pas
 * encore installé, pas une panne à montrer au visiteur.
 */
function config(): array
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg  = config_defaults();
        $path = __DIR__ . '/config.php';
        if (is_file($path)) {
            $file = @require $path;
            if (is_array($file)) {
                // Fusion sur un seul niveau : 'db' et 'recaptcha' sont
                // remplacés en bloc, ce qui est bien ce qu'on veut.
                $cfg = array_merge($cfg, $file);
            }
        }
    }
    return $cfg;
}

/** Coupe court sur les points d'entrée qui ont réellement besoin de la base. */
function require_installed(): void
{
    if (config_installed() && cfg('db')) {
        return;
    }
    json_out([
        'error'     => 'Le back-office n’est pas encore installé : api/config.php est '
                     . 'absent ou ne contient pas les identifiants de la base.',
        'not_setup' => true,
        'setup_url' => 'setup.php',
    ], 503);
}

function cfg(string $key, $default = null)
{
    $parts = explode('.', $key);
    $node  = config();
    foreach ($parts as $p) {
        if (!is_array($node) || !array_key_exists($p, $node)) {
            return $default;
        }
        $node = $node[$p];
    }
    return $node;
}

// ------------------------------------------------------------------ erreurs

// Les détails partent dans le journal du serveur, jamais dans la réponse.
ini_set('display_errors', cfg('debug') ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

set_exception_handler(static function (Throwable $e): void {
    error_log('[alamstores] ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(cfg('debug')
        ? ['error' => $e->getMessage()]
        : ['error' => 'Erreur interne. Réessayez ou consultez le journal du serveur.']);
    exit;
});

// -------------------------------------------------------- en-têtes de sécurité

function send_security_headers(bool $html = false): void
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=(), interest-cohort=()');
    header('Cross-Origin-Opener-Policy: same-origin');
    header_remove('X-Powered-By');
    if ($html) {
        // Le back-office n'a aucune dépendance externe : tout est verrouillé
        // sur same-origin. 'unsafe-inline' couvre le style et le script inline
        // du tableau de bord, qui est un fichier unique.
        header("Content-Security-Policy: default-src 'self'; "
             . "img-src 'self' data: blob:; "
             . "style-src 'self' 'unsafe-inline'; "
             . "script-src 'self' 'unsafe-inline'; "
             . "frame-src https://www.youtube-nocookie.com https://www.youtube.com; "
             . "connect-src 'self'; form-action 'self'; base-uri 'none'; "
             . "object-src 'none'; frame-ancestors 'self'");
    }
    if (!empty($_SERVER['HTTPS']) || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

// ------------------------------------------------------------------- base

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $d = cfg('db');
        // Un `dsn` explicite l'emporte : utile pour une connexion par socket
        // Unix, ou pour la base de test utilisée par tools/test_api.php.
        $dsn = $d['dsn'] ?? sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
                                    $d['host'], (int) $d['port'], $d['name'], $d['charset']);
        $pdo = new PDO($dsn, $d['user'] ?? null, $d['password'] ?? null, [
            // Requêtes préparées côté serveur : pas d'échappement fait maison,
            // donc pas d'injection SQL possible par concaténation.
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
        ]);
    }
    return $pdo;
}

// ---------------------------------------------------------------- session

function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = !empty($_SERVER['HTTPS']) || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,      // inaccessible au JavaScript
        'secure'   => $secure,   // HTTPS uniquement quand le site est en HTTPS
        'samesite' => 'Strict',  // le cookie ne part pas depuis un autre site
    ]);
    session_name('ALAMSID');
    session_start();

    // Expiration par inactivité.
    if (isset($_SESSION['last_seen']) && time() - $_SESSION['last_seen'] > SESSION_LIFETIME) {
        logout();
    }
    $_SESSION['last_seen'] = time();
}

function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'],
                  $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): array
{
    start_session();
    $user = current_user();
    if (!$user) {
        json_out(['error' => 'Session expirée. Reconnectez-vous.'], 401);
    }
    return $user;
}

// -------------------------------------------------------------------- CSRF

function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Vérifie le jeton anti-CSRF de toute requête qui modifie des données. */
function require_csrf(array $body = []): void
{
    start_session();
    $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($body['csrf'] ?? ($_POST['csrf'] ?? ''));
    if (!is_string($sent) || empty($_SESSION['csrf'])
        || !hash_equals($_SESSION['csrf'], $sent)) {
        json_out(['error' => 'Jeton de sécurité invalide. Rechargez la page.'], 403);
    }
}

// ------------------------------------------------------------------- entrées

/** Corps JSON de la requête, borné en taille. */
function json_body(): array
{
    $raw = file_get_contents('php://input', false, null, 0, MAX_BODY_BYTES + 1);
    if ($raw === false || $raw === '') {
        return [];
    }
    if (strlen($raw) > MAX_BODY_BYTES) {
        json_out(['error' => 'Requête trop volumineuse.'], 413);
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/** Texte nettoyé : caractères de contrôle retirés, longueur bornée. */
function clean_text($value, int $max = 255): string
{
    if (!is_scalar($value)) {
        return '';
    }
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string) $value);
    $v = trim((string) $v);
    return mb_substr($v, 0, $max, 'UTF-8');
}

function clean_multiline($value, int $max = 5000): string
{
    if (!is_scalar($value)) {
        return '';
    }
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string) $value);
    $v = preg_replace("/\r\n?/", "\n", (string) $v);
    return mb_substr(trim($v), 0, $max, 'UTF-8');
}

/** Slug ASCII sûr pour une URL et pour un nom de fichier. */
function slugify(string $text, string $fallback = 'produit'): string
{
    $t = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $t = strtolower((string) $t);
    $t = preg_replace('/[^a-z0-9]+/', '-', $t);
    $t = trim((string) $t, '-');
    return $t !== '' ? substr($t, 0, 150) : $fallback;
}

/**
 * Identifiant YouTube extrait d'une URL, ou null.
 *
 * Seul l'identifiant est conservé : l'URL du visiteur ne peut donc pas
 * transporter de paramètre arbitraire jusqu'au <iframe>.
 */
function youtube_id(string $url): ?string
{
    $url = trim($url);
    if ($url === '') {
        return null;
    }
    $patterns = [
        '~youtube\.com/watch\?(?:.*&)?v=([A-Za-z0-9_-]{11})~i',
        '~youtu\.be/([A-Za-z0-9_-]{11})~i',
        '~youtube\.com/embed/([A-Za-z0-9_-]{11})~i',
        '~youtube\.com/shorts/([A-Za-z0-9_-]{11})~i',
        '~youtube-nocookie\.com/embed/([A-Za-z0-9_-]{11})~i',
    ];
    foreach ($patterns as $re) {
        if (preg_match($re, $url, $m)) {
            return $m[1];
        }
    }
    // Un identifiant collé tel quel.
    if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
        return $url;
    }
    return null;
}

/** HMAC tronqué de l'IP : suffisant pour limiter le spam, non réversible. */
function ip_hash(): string
{
    $ip   = $_SERVER['REMOTE_ADDR'] ?? '';
    $salt = (string) cfg('ip_salt', '');
    if ($ip === '' || $salt === '') {
        return '';
    }
    return substr(hash_hmac('sha256', $ip, $salt), 0, 32);
}

// ------------------------------------------------------------------- sorties

function json_out($data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** Refuse toute méthode HTTP non listée. */
function require_method(string ...$methods): string
{
    $m = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if (!in_array($m, $methods, true)) {
        header('Allow: ' . implode(', ', $methods));
        json_out(['error' => 'Méthode non autorisée.'], 405);
    }
    return $m;
}

/**
 * L'API publique est en même-origine. On n'ouvre CORS que pour les origines
 * explicitement listées dans la configuration.
 */
function apply_cors(): void
{
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if ($origin === '') {
        return;
    }
    $allowed = (array) cfg('allowed_origins', []);
    if (in_array($origin, $allowed, true)) {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Vary: Origin');
    }
}
