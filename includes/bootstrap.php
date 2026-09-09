<?php
declare(strict_types=1);

/**
 * Socle des pages publiques.
 *
 * Chargé par includes/header.php, donc par toutes les pages. Il donne accès à
 * la base quand elle est configurée, sans jamais faire échouer une page si
 * elle ne l'est pas : le site doit rester lisible même base éteinte.
 */

if (defined('ALAM_BOOTSTRAP')) {
    return;
}
define('ALAM_BOOTSTRAP', true);

define('ALAM_ROOT', dirname(__DIR__));

// Cible des boutons « Demander un devis » posés par le code PHP. Doit suivre
// PAGE_EXT dans tools/build_pages.py.
define('ALAM_LINK_DEVIS', 'devis.php');

/** Échappement HTML, utilisé partout dans les gabarits. */
function alam_e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Connexion à la base, ou null.
 *
 * Renvoie null si api/config.php n'existe pas encore (site pas installé) ou si
 * la base est injoignable. Les appelants affichent alors leur repli statique.
 */
function alam_db(): ?PDO
{
    static $pdo = false;
    if ($pdo !== false) {
        return $pdo;
    }
    $pdo = null;
    if (!is_file(ALAM_ROOT . '/api/config.php')) {
        return null;
    }
    try {
        require_once ALAM_ROOT . '/api/bootstrap.php';
        $pdo = db();
        // api/bootstrap.php installe un gestionnaire d'erreurs qui répond en
        // JSON : correct pour l'API, illisible dans une page. On le remplace.
        set_exception_handler(static function (Throwable $e): void {
            error_log('[alamstores] ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
            http_response_code(500);
            echo '<p style="font:16px system-ui;padding:40px">Une erreur est survenue. '
               . 'Réessayez dans un instant.</p>';
        });
    } catch (Throwable $e) {
        error_log('[alamstores] base injoignable : ' . $e->getMessage());
        $pdo = null;
    }
    return $pdo;
}

/** Configuration, tolérante à l'absence de fichier. */
function alam_cfg(string $key, $default = null)
{
    if (!is_file(ALAM_ROOT . '/api/config.php')) {
        return $default;
    }
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require ALAM_ROOT . '/api/config.php';
        if (!is_array($cfg)) {
            $cfg = [];
        }
    }
    $node = $cfg;
    foreach (explode('.', $key) as $part) {
        if (!is_array($node) || !array_key_exists($part, $node)) {
            return $default;
        }
        $node = $node[$part];
    }
    return $node;
}

/** Session ouverte à la demande : elle ne sert qu'au retour du formulaire. */
/**
 * Ouvre la session à la demande.
 *
 * ATTENTION : session_start() échoue une fois le moindre octet envoyé au
 * navigateur. Les pages appellent donc alam_flash() depuis includes/header.php,
 * avant l'ouverture du document — pas au moment d'afficher le formulaire.
 */
function alam_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    if (headers_sent($file, $line)) {
        error_log("[alamstores] session ouverte trop tard, sortie déjà commencée "
                  . "($file:$line)");
        return;
    }
    $secure = !empty($_SERVER['HTTPS']) || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'httponly' => true,
        'secure' => $secure, 'samesite' => 'Lax',
    ]);
    session_name('ALAMSID');
    session_start();
}

/**
 * Valeurs et erreurs renvoyées par api/lead.php après un envoi refusé.
 *
 * Lues une seule fois : un rafraîchissement de la page repart d'un formulaire
 * vierge plutôt que de réafficher d'anciennes erreurs.
 */
function alam_flash(): array
{
    static $flash = null;
    if ($flash === null) {
        $flash = ['old' => [], 'errors' => [], 'message' => ''];
        if (!empty($_COOKIE['ALAMSID']) || session_status() === PHP_SESSION_ACTIVE) {
            alam_session();
            if (!empty($_SESSION['devis'])) {
                $flash = $_SESSION['devis'] + $flash;
                unset($_SESSION['devis']);
            }
        }
    }
    return $flash;
}

/** Valeur précédemment saisie, réaffichée après une erreur. */
function alam_old(string $field, string $default = ''): string
{
    $flash = alam_flash();
    return alam_e($flash['old'][$field] ?? $default);
}

/** Message d'erreur pour un champ, ou chaîne vide. */
function alam_err(string $field): string
{
    $flash = alam_flash();
    return alam_e($flash['errors'][$field] ?? '');
}

/** `selected` quand une liste déroulante doit reprendre la valeur saisie. */
function alam_selected(string $field, string $value): string
{
    $flash = alam_flash();
    return (($flash['old'][$field] ?? null) === $value) ? ' selected' : '';
}
