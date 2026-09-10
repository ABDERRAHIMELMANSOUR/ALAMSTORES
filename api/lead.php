<?php
declare(strict_types=1);

/**
 * Réception des demandes de devis envoyées par devis.php.
 *
 * Le formulaire est un formulaire HTML ordinaire : il fonctionne sans
 * JavaScript. Le déroulé est toujours le même — vérifier le reCAPTCHA,
 * contrôler les champs, enregistrer, prévenir par e-mail, puis rediriger le
 * visiteur vers WhatsApp avec sa demande déjà rédigée.
 *
 * En cas d'erreur, on renvoie vers le formulaire avec les valeurs saisies et
 * les messages, plutôt que d'afficher une page d'erreur.
 */

require __DIR__ . '/bootstrap.php';
require_once dirname(__DIR__) . '/includes/recaptcha.php';

require_method('POST');
send_security_headers();

const DEVIS_PAGE = '../devis.php';

/** Renvoie au formulaire en conservant la saisie et les erreurs. */
function back_to_form(array $old, array $errors, string $message): void
{
    alam_session();
    unset($old['website']);
    $_SESSION['devis'] = ['old' => $old, 'errors' => $errors, 'message' => $message];
    header('Location: ' . DEVIS_PAGE . '#devis-form', true, 303);
    exit;
}

/** Renvoie au formulaire avec un accusé de réception, sans la saisie. */
function back_confirmed(string $message): void
{
    alam_session();
    $_SESSION['devis'] = ['old' => [], 'errors' => [],
                          'message' => $message, 'success' => true];
    header('Location: ' . DEVIS_PAGE . '#devis-form', true, 303);
    exit;
}

// Une requête de navigateur qui vient d'ailleurs que du site est refusée.
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && !in_array($origin, (array) cfg('allowed_origins', []), true)) {
    json_out(['error' => 'Origine non autorisée.'], 403);
}

$lead = [
    'statut'    => clean_text($_POST['statut']    ?? '', 80),
    'company'   => clean_text($_POST['company']   ?? '', 180),
    'category'  => clean_text($_POST['category']  ?? '', 120),
    'firstname' => clean_text($_POST['firstname'] ?? '', 120),
    'lastname'  => clean_text($_POST['lastname']  ?? '', 120),
    'email'     => clean_text($_POST['email']     ?? '', 190),
    'phone'     => clean_text($_POST['phone']     ?? '', 60),
    'address'   => clean_text($_POST['address']   ?? '', 240),
    'zip'       => clean_text($_POST['zip']       ?? '', 20),
    'city'      => clean_text($_POST['city']      ?? '', 120),
    'country'   => clean_text($_POST['country']   ?? '', 120),
    'message'   => clean_multiline($_POST['message'] ?? '', 5000),
];

// Champ piège : invisible pour un humain, rempli par la plupart des robots.
// On acquiesce sans rien enregistrer, pour ne pas renseigner le robot.
if (clean_text($_POST['website'] ?? '', 100) !== '') {
    header('Location: ' . DEVIS_PAGE, true, 303);
    exit;
}

// ------------------------------------------------------------- reCAPTCHA ---
if (!alam_recaptcha_verify((string) ($_POST['g-recaptcha-response'] ?? ''))) {
    back_to_form($lead, ['recaptcha' => 'Merci de confirmer que vous n’êtes pas un robot.'],
                 'La vérification anti-robot n’a pas abouti. Réessayez.');
}

// -------------------------------------------------------------- contrôles ---
$errors = [];
foreach (['firstname' => 'Prénom', 'lastname' => 'Nom', 'message' => 'Message',
          'statut' => 'Statut professionnel', 'category' => 'Catégorie'] as $k => $label) {
    if ($lead[$k] === '') {
        $errors[$k] = $label . ' obligatoire.';
    }
}
if ($lead['email'] === '' || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Adresse e-mail invalide.';
}
if (strlen(preg_replace('/\D/', '', $lead['phone'])) < 8) {
    $errors['phone'] = 'Numéro de téléphone incomplet.';
}
if ($errors) {
    back_to_form($lead, $errors, 'Merci de corriger les champs signalés.');
}

// ---------------------------------------------------- limite anti-flood ----
$hash = ip_hash();
$pdo  = null;
try {
    $pdo = db();
    if ($hash !== '') {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM leads
                                WHERE ip_hash = ? AND created_at > ?');
        $stmt->execute([$hash, gmdate('Y-m-d H:i:s', time() - 3600)]);
        if ((int) $stmt->fetchColumn() >= 5) {
            back_to_form($lead, [],
                'Vous avez déjà envoyé plusieurs demandes dans l’heure. '
                . 'Réessayez plus tard, ou appelez-nous directement.');
        }
    }

    $stmt = $pdo->prepare(
        'INSERT INTO leads (statut, company, category, firstname, lastname, email, phone,
                            address, zip, city, country, message, source, ip_hash, user_agent)
         VALUES (:statut, :company, :category, :firstname, :lastname, :email, :phone,
                 :address, :zip, :city, :country, :message, :source, :ip_hash, :user_agent)');
    $stmt->execute($lead + [
        'source'     => 'devis.php',
        'ip_hash'    => $hash,
        'user_agent' => clean_text($_SERVER['HTTP_USER_AGENT'] ?? '', 255),
    ]);
} catch (Throwable $e) {
    // Base indisponible : on n'arrête pas le visiteur pour autant. Sa demande
    // part quand même par e-mail et par WhatsApp — on perd la ligne en base,
    // pas le client.
    error_log('[alamstores] lead non enregistré : ' . $e->getMessage());
}

// ------------------------------------------------------ mise en forme -------
const LEAD_LABELS = [
    'statut' => 'Statut', 'company' => 'Entreprise', 'category' => 'Catégorie',
    'firstname' => 'Prénom', 'lastname' => 'Nom', 'email' => 'E-mail',
    'phone' => 'Téléphone', 'address' => 'Adresse', 'zip' => 'Code postal',
    'city' => 'Ville', 'country' => 'Pays',
];

$who = $lead['company'] !== '' ? $lead['company']
                               : trim($lead['firstname'] . ' ' . $lead['lastname']);

// ---------------------------------------------------------- notification ---
$recipients = array_values(array_filter(
    array_map('trim', (array) cfg('notify_email', [])),
    static function ($address) { return filter_var($address, FILTER_VALIDATE_EMAIL); }));

if ($recipients) {
    $lines = ['Nouvelle demande de devis', ''];
    foreach (LEAD_LABELS as $k => $label) {
        if ($lead[$k] !== '') {
            $lines[] = "$label : " . $lead[$k];
        }
    }
    $lines[] = '';
    $lines[] = 'Message :';
    $lines[] = $lead['message'];

    $subject = '=?UTF-8?B?' . base64_encode('Devis — ' . ($lead['category'] ?: 'projet')
                                            . ($who !== '' ? " — $who" : '')) . '?=';
    // En-têtes construits à partir de valeurs déjà validées : clean_text a
    // retiré les sauts de ligne, donc aucune injection d'en-tête possible.
    $host = preg_replace('/[^a-z0-9.\-]/i', '', (string) ($_SERVER['HTTP_HOST'] ?? 'alamstores.ma'));
    @mail(implode(', ', $recipients), $subject, implode("\n", $lines), implode("\r\n", [
        'From: Site Alam Stores <no-reply@' . $host . '>',
        'Reply-To: ' . $lead['email'],
        'Content-Type: text/plain; charset=UTF-8',
        'X-Mailer: alamstores',
    ]));
}

// ------------------------------------------------------------ destination ---
// Le bouton choisi décide de ce qui arrive au visiteur. Dans les deux cas la
// demande est déjà en base et l'e-mail de notification est parti : c'est le
// canal de réponse qui change, pas l'enregistrement.
if (($_POST['channel'] ?? 'whatsapp') === 'email') {
    back_confirmed($recipients
        ? 'Merci, votre demande nous est bien parvenue. Nous vous répondons sous 24 h ouvrées.'
        : 'Votre demande a bien été enregistrée. Nous vous répondons sous 24 h ouvrées.');
}

// -------------------------------------------------------------- WhatsApp ---
// WhatsApp met en gras ce qui est entouré d'astérisques : les intitulés
// ressortent dans la conversation.
$lines = ['*Demande de devis — alamstores.ma*', ''];
foreach (LEAD_LABELS as $k => $label) {
    if ($lead[$k] !== '') {
        $lines[] = '*' . $label . '* : ' . $lead[$k];
    }
}
if ($lead['message'] !== '') {
    $lines[] = '';
    $lines[] = '*Message :*';
    $lines[] = $lead['message'];
}

$number = preg_replace('/\D/', '', (string) cfg('whatsapp', '212600055562'));
header('Location: https://wa.me/' . $number . '?text=' . rawurlencode(implode("\n", $lines)),
       true, 303);
exit;
