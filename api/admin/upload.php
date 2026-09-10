<?php
declare(strict_types=1);

/**
 * Téléversement des photos et des fiches techniques.
 *
 *   POST api/admin/upload.php   (multipart/form-data : file, kind=photo|doc)
 *
 * Le nom d'origine n'est jamais réutilisé : chaque fichier est renommé en
 * aléatoire, avec une extension déduite du contenu réel et non de ce que le
 * navigateur annonce. Combiné au .htaccess de assets/uploads/ qui coupe
 * l'exécution PHP, cela ferme la voie classique du webshell téléversé —
 * exactement ce qui avait été trouvé sur l'ancien site WordPress.
 */

require dirname(__DIR__) . '/bootstrap.php';

require_method('POST');
send_security_headers();
require_installed();
require_login();
require_csrf();

const MAX_PHOTO_BYTES = 8388608;    // 8 Mo
const MAX_DOC_BYTES   = 15728640;   // 15 Mo

// Extensions autorisées, indexées par type MIME réellement détecté.
const PHOTO_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif',
];
const DOC_TYPES = ['application/pdf' => 'pdf'];

$kind = clean_text($_POST['kind'] ?? 'photo', 10);
if (!in_array($kind, ['photo', 'doc'], true)) {
    json_out(['error' => 'Type de fichier inconnu.'], 400);
}

if (!isset($_FILES['file']) || !is_array($_FILES['file'])) {
    json_out(['error' => 'Aucun fichier reçu.'], 400);
}
$file = $_FILES['file'];

if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $messages = [
        UPLOAD_ERR_INI_SIZE   => 'Fichier trop volumineux pour le serveur.',
        UPLOAD_ERR_FORM_SIZE  => 'Fichier trop volumineux.',
        UPLOAD_ERR_PARTIAL    => 'Téléversement interrompu.',
        UPLOAD_ERR_NO_FILE    => 'Aucun fichier reçu.',
        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire absent sur le serveur.',
        UPLOAD_ERR_CANT_WRITE => 'Écriture impossible sur le serveur.',
    ];
    json_out(['error' => $messages[$file['error']] ?? 'Téléversement échoué.'], 400);
}

// Garantit que le fichier vient bien d'un POST HTTP et non d'un chemin forgé.
if (!is_uploaded_file($file['tmp_name'])) {
    json_out(['error' => 'Fichier invalide.'], 400);
}

$max = $kind === 'photo' ? MAX_PHOTO_BYTES : MAX_DOC_BYTES;
if ((int) $file['size'] <= 0 || (int) $file['size'] > $max) {
    json_out(['error' => sprintf('Fichier trop volumineux (maximum %d Mo).', intdiv($max, 1048576))], 413);
}

// Type MIME lu dans le contenu, pas dans l'en-tête envoyé par le navigateur.
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = (string) $finfo->file($file['tmp_name']);

if ($kind === 'photo') {
    if (!isset(PHOTO_TYPES[$mime])) {
        json_out(['error' => 'Format d’image non accepté (JPG, PNG, WebP ou GIF).'], 415);
    }
    // Deuxième lecture, indépendante : un fichier qui n'est pas une vraie
    // image ne passe pas getimagesize, même s'il commence par les bons octets.
    $info = @getimagesize($file['tmp_name']);
    if (!$info || empty($info[0]) || empty($info[1])) {
        json_out(['error' => 'Ce fichier n’est pas une image valide.'], 415);
    }
    $ext    = PHOTO_TYPES[$mime];
    $subdir = 'photos';
} else {
    if (!isset(DOC_TYPES[$mime])) {
        json_out(['error' => 'Seuls les PDF sont acceptés comme fiche technique.'], 415);
    }
    if (strncmp((string) file_get_contents($file['tmp_name'], false, null, 0, 5), '%PDF-', 5) !== 0) {
        json_out(['error' => 'Ce fichier n’est pas un PDF valide.'], 415);
    }
    $ext    = 'pdf';
    $subdir = 'docs';
}

// Un fichier contenant du PHP n'a rien à faire ici, quelle que soit son
// extension : ceinture et bretelles par-dessus le .htaccess.
$head = (string) file_get_contents($file['tmp_name'], false, null, 0, 65536);
if (stripos($head, '<?php') !== false || stripos($head, '<?=') !== false) {
    json_out(['error' => 'Fichier refusé : il contient du code exécutable.'], 415);
}

$root = dirname(__DIR__, 2);
$dir  = $root . '/assets/uploads/' . $subdir;
if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
    json_out(['error' => 'Dossier de destination indisponible.'], 500);
}

// Nom aléatoire : rien de ce que l'utilisateur envoie n'atteint le disque.
$name = date('Ym') . '-' . bin2hex(random_bytes(8)) . '.' . $ext;
$dest = $dir . '/' . $name;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    json_out(['error' => 'Enregistrement du fichier impossible.'], 500);
}
@chmod($dest, 0644);

json_out([
    'ok'         => true,
    'path'       => 'assets/uploads/' . $subdir . '/' . $name,
    'size_bytes' => filesize($dest) ?: 0,
    'label'      => $kind === 'doc'
        ? clean_text(pathinfo((string) $file['name'], PATHINFO_FILENAME), 200)
        : '',
], 201);
