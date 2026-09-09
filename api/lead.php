<?php
declare(strict_types=1);

/**
 * Réception des demandes de devis envoyées par devis.html.
 *
 *   POST api/lead.php   (corps JSON)
 *
 * La page continue d'ouvrir WhatsApp et de garder une copie locale : cet
 * endpoint est la troisième couche, celle qui centralise réellement les
 * demandes dans la base, quel que soit l'appareil du visiteur.
 */

require __DIR__ . '/bootstrap.php';

require_method('POST', 'OPTIONS');
send_security_headers();
apply_cors();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    http_response_code(204);
    exit;
}

// Une requête de navigateur qui vient d'ailleurs que du site est refusée.
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && !in_array($origin, (array) cfg('allowed_origins', []), true)) {
    json_out(['error' => 'Origine non autorisée.'], 403);
}

$body = json_body();

// Champ piège : invisible pour un humain, rempli par la plupart des robots.
if (clean_text($body['website'] ?? '', 100) !== '') {
    json_out(['ok' => true, 'id' => 0]);   // on acquiesce sans rien enregistrer
}

$lead = [
    'statut'    => clean_text($body['statut']    ?? '', 80),
    'company'   => clean_text($body['company']   ?? '', 180),
    'category'  => clean_text($body['category']  ?? '', 120),
    'firstname' => clean_text($body['firstname'] ?? '', 120),
    'lastname'  => clean_text($body['lastname']  ?? '', 120),
    'email'     => clean_text($body['email']     ?? '', 190),
    'phone'     => clean_text($body['phone']     ?? '', 60),
    'address'   => clean_text($body['address']   ?? '', 240),
    'zip'       => clean_text($body['zip']       ?? '', 20),
    'city'      => clean_text($body['city']      ?? '', 120),
    'country'   => clean_text($body['country']   ?? '', 120),
    'message'   => clean_multiline($body['message'] ?? '', 5000),
];

$errors = [];
foreach (['firstname' => 'Prénom', 'lastname' => 'Nom', 'message' => 'Message'] as $k => $label) {
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
    json_out(['error' => 'Formulaire incomplet.', 'fields' => $errors], 422);
}

$pdo  = db();
$hash = ip_hash();

// Limite anti-flood : 5 demandes par heure et par appareil. Sans sel configuré
// le hash est vide, et la limite ne s'applique pas — c'est volontaire, mieux
// vaut accepter un lead que d'en perdre un.
if ($hash !== '') {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM leads
                            WHERE ip_hash = ? AND created_at > ?');
    $stmt->execute([$hash, gmdate('Y-m-d H:i:s', time() - 3600)]);
    if ((int) $stmt->fetchColumn() >= 5) {
        json_out(['error' => 'Trop de demandes envoyées. Réessayez dans une heure ou appelez-nous.'], 429);
    }
}

$stmt = $pdo->prepare(
    'INSERT INTO leads (statut, company, category, firstname, lastname, email, phone,
                        address, zip, city, country, message, source, ip_hash, user_agent)
     VALUES (:statut, :company, :category, :firstname, :lastname, :email, :phone,
             :address, :zip, :city, :country, :message, :source, :ip_hash, :user_agent)');
$stmt->execute($lead + [
    'source'     => clean_text($body['source'] ?? 'devis.html', 40),
    'ip_hash'    => $hash,
    'user_agent' => clean_text($_SERVER['HTTP_USER_AGENT'] ?? '', 255),
]);
$id = (int) $pdo->lastInsertId();

// Notification, en meilleur effort : un e-mail qui ne part pas ne doit jamais
// faire échouer l'enregistrement du lead.
$recipients = array_values(array_filter(
    array_map('trim', (array) cfg('notify_email', [])),
    static function ($address) { return filter_var($address, FILTER_VALIDATE_EMAIL); }));

if ($recipients) {
    $labels = [
        'statut' => 'Statut', 'company' => 'Entreprise', 'category' => 'Catégorie',
        'firstname' => 'Prénom', 'lastname' => 'Nom', 'email' => 'E-mail',
        'phone' => 'Téléphone', 'address' => 'Adresse', 'zip' => 'Code postal',
        'city' => 'Ville', 'country' => 'Pays',
    ];
    $lines = ["Nouvelle demande de devis (#$id)", ''];
    foreach ($labels as $k => $label) {
        if ($lead[$k] !== '') {
            $lines[] = "$label : " . $lead[$k];
        }
    }
    $lines[] = '';
    $lines[] = 'Message :';
    $lines[] = $lead['message'];

    $who     = $lead['company'] !== '' ? $lead['company']
                                       : trim($lead['firstname'] . ' ' . $lead['lastname']);
    $subject = '=?UTF-8?B?' . base64_encode('Devis — ' . ($lead['category'] ?: 'projet')
                                            . ($who !== '' ? " — $who" : '')) . '?=';
    // En-têtes construits à partir de valeurs validées uniquement : ni le
    // sujet ni le Reply-To ne peuvent porter de saut de ligne (clean_text les
    // retire) — pas d'injection d'en-tête possible.
    @mail(implode(', ', $recipients), $subject, implode("\n", $lines), implode("\r\n", [
        'From: Site Alam Stores <no-reply@' . preg_replace('/[^a-z0-9.\-]/i', '', (string) ($_SERVER['HTTP_HOST'] ?? 'alamstores.ma')) . '>',
        'Reply-To: ' . $lead['email'],
        'Content-Type: text/plain; charset=UTF-8',
        'X-Mailer: alamstores',
    ]));
}

json_out(['ok' => true, 'id' => $id], 201);
