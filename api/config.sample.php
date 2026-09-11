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
        // « localhost » ne répond pas partout : sur beaucoup d'hébergements
        // (Webuzo, CloudLinux…) il passe par un socket Unix dont le chemin
        // n'est pas celui par défaut, alors que 127.0.0.1 passe par le réseau.
        // admin/setup.php essaie les deux et écrit ici celui qui marche.
        //
        // Connexion par socket ? Remplacez host et port par, par exemple :
        //     'socket' => '/var/lib/mysql/mysql.sock',
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

    // Numéro WhatsApp vers lequel le formulaire de devis redirige, au format
    // international, chiffres uniquement. Doit rester identique à CONTACT
    // dans tools/build_pages.py.
    'whatsapp' => '212600055562',

    // ------------------------------------------------------------ reCAPTCHA
    // La clé du site est publique : elle apparaît dans le HTML de la page.
    // La clé secrète ne doit JAMAIS sortir de ce fichier, ni être versionnée.
    // Récupérez-la sur https://www.google.com/recaptcha/admin
    //
    // 'version' : 'v2' pour la case « Je ne suis pas un robot »,
    //             'v3' pour la vérification invisible par score.
    // Laissez 'secret_key' vide pour désactiver la vérification (le
    // formulaire continue de fonctionner, sans filtrage).
    'recaptcha' => [
        'site_key'   => '6LejfrItAAAAAMv9gSWoYpQYZfYUsJqQVACgbJqp',
        'secret_key' => '',        // <- à renseigner
        'version'    => 'v2',
        'min_score'  => 0.5,       // v3 uniquement
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
