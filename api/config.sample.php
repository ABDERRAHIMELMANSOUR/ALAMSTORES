<?php
/**
 * Copiez ce fichier en api/config.php et renseignez vos identifiants.
 *
 *     cp api/config.sample.php api/config.php
 *     chmod 640 api/config.php
 *
 * api/config.php est ignoré par git et bloqué par api/.htaccess : il ne doit
 * JAMAIS être versionné ni servi. (Le wp-config.php de l'ancien site avait été
 * commité en clair — voir SECURITY-AUDIT.md §3.)
 */

return [
    // ---------------------------------------------------------- base de données
    'db' => [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'name'     => 'alamstores',
        'user'     => 'alamstores',
        'password' => '',          // <- à renseigner
        'charset'  => 'utf8mb4',
    ],

    // Sel utilisé pour hacher les adresses IP (anti-spam sans conserver d'IP
    // en clair). Générez-en un une fois pour toutes :
    //     php -r "echo bin2hex(random_bytes(32));"
    'ip_salt' => '',               // <- à renseigner

    // Adresses qui reçoivent une notification à chaque nouvelle demande de
    // devis. Ajoutez-en autant que nécessaire ; laissez la liste vide pour
    // n'envoyer aucun e-mail (les demandes restent enregistrées en base).
    'notify_email' => [
        'contact@alamstores.ma',
        'kassettebrahim.1997@gmail.com',
    ],

    // Origines autorisées à appeler l'API (votre domaine, rien d'autre).
    'allowed_origins' => [
        'https://alamstores.ma',
        'https://www.alamstores.ma',
    ],

    // Passez à true seulement le temps d'un diagnostic : en production les
    // erreurs détaillées ne doivent jamais atteindre le navigateur.
    'debug' => false,
];
