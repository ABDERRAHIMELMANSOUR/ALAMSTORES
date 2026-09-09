<?php
declare(strict_types=1);

/**
 * reCAPTCHA sur le formulaire de devis.
 *
 * Prend en charge les deux variantes, choisies par `recaptcha.version` dans
 * api/config.php :
 *
 *   v2  la case « Je ne suis pas un robot » (par défaut)
 *   v3  invisible, qui renvoie un score entre 0 et 1
 *
 * Sans clé secrète configurée, la vérification est ignorée et le formulaire
 * continue de fonctionner : mieux vaut un site qui reçoit les demandes qu'un
 * formulaire bloqué par une configuration incomplète. L'absence est journalisée.
 */

require_once __DIR__ . '/bootstrap.php';

function alam_recaptcha_site_key(): string
{
    return (string) alam_cfg('recaptcha.site_key', '');
}

function alam_recaptcha_version(): string
{
    return alam_cfg('recaptcha.version', 'v2') === 'v3' ? 'v3' : 'v2';
}

function alam_recaptcha_enabled(): bool
{
    return alam_recaptcha_site_key() !== '';
}

/** Le widget, dans le formulaire. Rien n'est affiché si aucune clé n'est fournie. */
function alam_recaptcha_widget(): void
{
    if (!alam_recaptcha_enabled()) {
        return;
    }
    $key = alam_e(alam_recaptcha_site_key());

    if (alam_recaptcha_version() === 'v3') {
        // Invisible : le jeton est demandé au moment de l'envoi (assets/js/main.js).
        ?>
              <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
              <div class="field field--full" data-recaptcha data-version="v3" data-sitekey="<?= $key ?>">
                <p class="form-note">Ce formulaire est protégé par reCAPTCHA.
                   <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Confidentialité</a>
                   &middot;
                   <a href="https://policies.google.com/terms" target="_blank" rel="noopener">Conditions</a>.</p>
                <span class="field__error" data-error-for="recaptcha"><?= alam_err('recaptcha') ?></span>
              </div>
              <script src="https://www.google.com/recaptcha/api.js?render=<?= $key ?>" async defer></script>
        <?php
        return;
    }
    ?>
              <div class="field field--full" data-recaptcha data-version="v2">
                <div class="g-recaptcha" data-sitekey="<?= $key ?>"></div>
                <span class="field__error" data-error-for="recaptcha"><?= alam_err('recaptcha') ?></span>
              </div>
              <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php
}

/**
 * Vérifie le jeton auprès de Google.
 *
 * Renvoie true quand la demande peut passer : jeton valide, ou reCAPTCHA non
 * configuré, ou Google injoignable — un incident réseau chez Google ne doit
 * pas faire perdre un client.
 */
function alam_recaptcha_verify(string $token): bool
{
    $secret = (string) alam_cfg('recaptcha.secret_key', '');
    if ($secret === '') {
        error_log('[alamstores] reCAPTCHA non configuré : vérification ignorée.');
        return true;
    }
    if ($token === '') {
        return false;
    }

    $body = http_build_query([
        'secret'   => $secret,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);
    $url  = 'https://www.google.com/recaptcha/api/siteverify';
    $raw  = false;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
    }
    if ($raw === false && ini_get('allow_url_fopen')) {
        $raw = @file_get_contents($url, false, stream_context_create([
            'http' => ['method' => 'POST', 'timeout' => 8,
                       'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                       'content' => $body],
            'ssl'  => ['verify_peer' => true, 'verify_peer_name' => true],
        ]));
    }
    if ($raw === false) {
        error_log('[alamstores] reCAPTCHA injoignable : demande acceptée sans vérification.');
        return true;
    }

    $data = json_decode((string) $raw, true);
    if (!is_array($data) || empty($data['success'])) {
        return false;
    }
    // v3 renvoie un score ; v2 n'en renvoie pas et s'arrête ici.
    if (isset($data['score'])) {
        return (float) $data['score'] >= (float) alam_cfg('recaptcha.min_score', 0.5);
    }
    return true;
}
