<?php
declare(strict_types=1);

/**
 * Installateur en trois étapes, pour les hébergements sans accès SSH :
 * connexion à la base, création des tables, puis du premier compte.
 *
 * Il ne fonctionne QUE tant qu'aucun compte n'existe. Dès qu'un compte est
 * créé, cette page renvoie 403 — et il faut la supprimer, ce qu'elle propose
 * de faire elle-même à la dernière étape.
 *
 * Volontairement autonome : api/bootstrap.php a besoin de api/config.php, qui
 * n'existe pas encore au moment où l'on ouvre cette page.
 */

const ROOT = __DIR__ . '/..';
const CONFIG_PATH = ROOT . '/api/config.php';

session_name('ALAMSETUP');
session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'; "
     . "img-src 'self' data:; form-action 'self'; base-uri 'none'; object-src 'none'");

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function h($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Connexion PDO à partir des valeurs saisies ou du fichier existant. */
/**
 * Construit le DSN PDO. Copie de la fonction du même nom dans
 * api/bootstrap.php : cette page doit rester autonome, elle s'exécute avant
 * que api/config.php existe.
 */
function db_dsn(array $d): string
{
    $charset = $d['charset'] ?? 'utf8mb4';
    if (!empty($d['dsn'])) {
        return (string) $d['dsn'];
    }
    if (!empty($d['socket'])) {
        return sprintf('mysql:unix_socket=%s;dbname=%s;charset=%s',
                       $d['socket'], $d['name'] ?? '', $charset);
    }
    return sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
                   $d['host'] ?? 'localhost', (int) ($d['port'] ?? 3306),
                   $d['name'] ?? '', $charset);
}

function connect(array $db): PDO
{
    return new PDO(db_dsn($db), $db['user'] ?? null, $db['password'] ?? null, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
}

/**
 * Les adresses à essayer, dans l'ordre, pour joindre MySQL.
 *
 * « localhost » échoue sur beaucoup d'hébergements (Webuzo, CloudLinux…) :
 * il passe par un socket Unix dont le chemin n'est pas celui par défaut,
 * alors que 127.0.0.1 passe par le réseau et marche. Plutôt que de faire
 * deviner l'utilisateur, on essaie les possibilités courantes.
 */
function host_candidates(string $typed): array
{
    $out = [];
    $typed = trim($typed);
    if ($typed !== '' && strpos($typed, '/') === 0) {
        $out[] = ['socket' => $typed, 'label' => 'socket ' . $typed];
    } elseif ($typed !== '') {
        $out[] = ['host' => $typed, 'label' => $typed];
    }
    foreach (['localhost', '127.0.0.1'] as $h) {
        $out[] = ['host' => $h, 'label' => $h];
    }
    foreach ([
        '/var/lib/mysql/mysql.sock',
        '/tmp/mysql.sock',
        '/var/run/mysqld/mysqld.sock',
        '/usr/local/emps/var/mysql/mysql.sock',   // Webuzo
        '/usr/local/mysql/data/mysql.sock',
    ] as $sock) {
        $out[] = ['socket' => $sock, 'label' => 'socket ' . $sock];
    }

    // Dédoublonnage en gardant l'ordre : la valeur saisie reste prioritaire.
    $seen = [];
    return array_values(array_filter($out, static function ($c) use (&$seen) {
        $key = ($c['socket'] ?? '') . '|' . ($c['host'] ?? '');
        if (isset($seen[$key])) {
            return false;
        }
        $seen[$key] = true;
        return true;
    }));
}

/**
 * Essaie chaque adresse et renvoie la première qui répond.
 *
 * Renvoie [PDO, réglage retenu, journal des essais]. Si aucune ne marche, le
 * journal explique ce qui a été tenté — c'est ce qu'on affiche à l'écran.
 */
function connect_any(array $db): array
{
    $log = [];
    foreach (host_candidates((string) ($db['host'] ?? '')) as $candidate) {
        $attempt = $db;
        unset($attempt['host'], $attempt['socket']);
        if (isset($candidate['socket'])) {
            // Un socket qui n'existe pas : inutile d'attendre le délai réseau.
            if (!file_exists($candidate['socket'])) {
                continue;
            }
            $attempt['socket'] = $candidate['socket'];
        } else {
            $attempt['host'] = $candidate['host'];
        }
        try {
            $pdo = connect($attempt);
            return [$pdo, $attempt, $log];
        } catch (Throwable $e) {
            $log[] = $candidate['label'] . ' — ' . $e->getMessage();
        }
    }
    return [null, null, $log];
}

/** Le fichier de configuration, tel qu'il doit être écrit. */
function config_source(array $db, array $emails, string $salt, array $captcha): string
{
    $q = static function (string $v): string {
        return "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], $v) . "'";
    };
    $list = '';
    foreach ($emails as $mail) {
        $list .= "        " . $q($mail) . ",\n";
    }
    // Suivant ce qui a répondu : une adresse réseau, ou un socket Unix.
    $where = isset($db['socket'])
        ? "        'socket'   => " . $q($db['socket']) . ",\n"
        : "        'host'     => " . $q($db['host'] ?? 'localhost') . ",\n"
          . "        'port'     => " . (int) ($db['port'] ?? 3306) . ",\n";

    return "<?php\n"
        . "// Généré par admin/setup.php. Ne jamais versionner ce fichier.\n"
        . "return [\n"
        . "    'db' => [\n"
        . $where
        . "        'name'     => " . $q($db['name']) . ",\n"
        . "        'user'     => " . $q($db['user']) . ",\n"
        . "        'password' => " . $q($db['password']) . ",\n"
        . "        'charset'  => 'utf8mb4',\n"
        . "    ],\n"
        . "    'ip_salt' => " . $q($salt) . ",\n"
        . "    'whatsapp' => '212600055562',\n"
        . "    'notify_email' => [\n" . $list . "    ],\n"
        . "    'recaptcha' => [\n"
        . "        'site_key'   => " . $q($captcha['site']) . ",\n"
        . "        'secret_key' => " . $q($captcha['secret']) . ",\n"
        . "        'version'    => " . $q($captcha['version']) . ",\n"
        . "        'min_score'  => 0.5,\n"
        . "    ],\n"
        . "    'allowed_origins' => [\n"
        . "        'https://alamstores.ma',\n"
        . "        'https://www.alamstores.ma',\n"
        . "    ],\n"
        . "    'debug' => false,\n"
        . "];\n";
}

/** Découpe un fichier .sql en instructions. Le schéma n'a ni procédure ni DELIMITER. */
function sql_statements(string $path): array
{
    $sql = (string) file_get_contents($path);
    $sql = preg_replace('/^\s*--.*$/m', '', $sql);
    return array_values(array_filter(array_map('trim', explode(';', $sql)),
                                     static function ($s) { return $s !== ''; }));
}

// ------------------------------------------------------------------- état ---
$config    = is_file(CONFIG_PATH) ? (require CONFIG_PATH) : null;
$pdo       = null;
$hasTables = false;
$hasAdmin  = false;

if (is_array($config) && !empty($config['db'])) {
    try {
        $pdo = connect($config['db']);
    } catch (Throwable $e) {
        // Le fichier existe mais l'adresse ne répond plus : on retente les
        // autres formes avant de renvoyer l'utilisateur à l'étape 1.
        [$pdo] = connect_any($config['db']);
    }
    if ($pdo) {
        // Interroger la table est plus portable que SHOW TABLES, et répond à la
        // seule question qui compte : peut-on déjà lire les comptes ?
        try {
            $hasAdmin  = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn() > 0;
            $hasTables = true;
        } catch (Throwable $e) {
            $hasTables = false;      // tables pas encore créées : étape 2
        }
    }
}

// Installation terminée : la page se ferme définitivement.
if ($hasAdmin && empty($_POST['selfdestruct'])) {
    http_response_code(403);
    ?><!doctype html><html lang="fr"><head><meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow"><title>Installation terminée</title>
    <link rel="stylesheet" href="../assets/css/main.min.css"></head>
    <body style="padding:40px 20px;max-width:620px;margin:0 auto">
      <h1 style="font-size:1.2rem">Installation déjà effectuée</h1>
      <p>Un compte existe déjà. Cette page est désactivée.</p>
      <p><strong>Supprimez maintenant <code>admin/setup.php</code> de votre serveur</strong>
         (clic droit → Supprimer dans FileZilla), puis connectez-vous.</p>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
        <button class="btn btn--primary btn--sm" type="submit" name="selfdestruct" value="1">
          Supprimer ce fichier maintenant</button>
      </form>
      <p style="margin-top:20px"><a href="./">Aller au back-office</a></p>
    </body></html><?php
    exit;
}

// -------------------------------------------------------------- traitement ---
$errors   = [];
$notices  = [];
$step     = 1;
$attempts = [];        // journal des adresses essayées, affiché en cas d'échec
$configSource = null;

if ($hasAdmin && !empty($_POST['selfdestruct'])) {
    if (!hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) {
        http_response_code(403);
        exit('Jeton invalide.');
    }
    if (@unlink(__FILE__)) {
        header('Location: ./');
        exit;
    }
    http_response_code(500);
    exit('Suppression impossible : effacez admin/setup.php manuellement avec FileZilla.');
}

if ($pdo && $hasTables) {
    $step = 3;
} elseif ($pdo) {
    $step = 2;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) {
        http_response_code(403);
        exit('Jeton invalide. Rechargez la page.');
    }

    $action = (string) ($_POST['action'] ?? '');

    // Dossier api/ non inscriptible : plutôt que de faire recopier le fichier à
    // la main dans un éditeur — où un mauvais encodage casse tout — on le sert
    // en téléchargement, prêt à être déposé par FTP.
    if ($action === 'download') {
        $source = (string) ($_POST['source'] ?? '');
        if ($source === '' || strncmp($source, '<?php', 5) !== 0) {
            http_response_code(400);
            exit('Contenu invalide.');
        }
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="config.php"');
        header('Content-Length: ' . strlen($source));
        header('Cache-Control: no-store');
        echo $source;
        exit;
    }

    // --- étape 1 : la connexion et le fichier de configuration --------------
    if ($action === 'config') {
        $db = [
            'host'     => trim((string) ($_POST['host'] ?? '127.0.0.1')),
            'port'     => (int) ($_POST['port'] ?? 3306) ?: 3306,
            'name'     => trim((string) ($_POST['name'] ?? '')),
            'user'     => trim((string) ($_POST['user'] ?? '')),
            'password' => (string) ($_POST['password'] ?? ''),
        ];
        $emails = array_values(array_filter(array_map('trim',
            preg_split('/[\s,;]+/', (string) ($_POST['emails'] ?? ''))),
            static function ($m) { return filter_var($m, FILTER_VALIDATE_EMAIL); }));

        $captcha = [
            'site'    => trim((string) ($_POST['rc_site'] ?? '')),
            'secret'  => trim((string) ($_POST['rc_secret'] ?? '')),
            'version' => ($_POST['rc_version'] ?? 'v2') === 'v3' ? 'v3' : 'v2',
        ];

        if ($db['name'] === '' || $db['user'] === '') {
            $errors[] = 'Nom de la base et utilisateur sont obligatoires.';
        } else {
            [$pdo, $working, $log] = connect_any($db);
            if (!$pdo) {
                $attempts = $log;
                $errors[] = 'Aucune des adresses essayées ne répond. Vérifiez le nom '
                          . 'de la base, l’utilisateur et le mot de passe — et que '
                          . 'l’utilisateur est bien rattaché à la base avec tous les '
                          . 'privilèges.';
            } else {
                $db = $working;
                if (isset($db['socket'])) {
                    $notices[] = 'Connexion réussie par le socket ' . $db['socket']
                               . ' (« localhost » ne répondait pas).';
                } elseif (($db['host'] ?? '') !== trim((string) ($_POST['host'] ?? ''))) {
                    $notices[] = 'Connexion réussie sur ' . $db['host']
                               . ' (l’adresse saisie ne répondait pas).';
                }
                $source = config_source($db, $emails, bin2hex(random_bytes(32)), $captcha);
                if (@file_put_contents(CONFIG_PATH, $source) !== false) {
                    @chmod(CONFIG_PATH, 0640);
                    $notices[] = 'api/config.php a été créé.';
                    $step = 2;
                } else {
                    // Dossier non inscriptible : on affiche le fichier à déposer.
                    $configSource = $source;
                    $errors[] = 'Connexion réussie, mais api/config.php n’a pas pu être '
                              . 'écrit : le dossier api/ n’autorise pas l’écriture. '
                              . 'Téléchargez le fichier ci-dessous, envoyez-le dans '
                              . 'api/ avec FileZilla, puis rechargez cette page.';
                }
            }
        }
    }

    // --- étape 2 : les tables ----------------------------------------------
    if ($action === 'schema' && $pdo) {
        try {
            foreach (['/db/schema.sql', '/db/seed_categories.sql'] as $file) {
                $path = ROOT . $file;
                if (!is_file($path)) {
                    throw new RuntimeException("Fichier manquant : $file (envoyez le dossier db/ par FTP).");
                }
                foreach (sql_statements($path) as $statement) {
                    $pdo->exec($statement);
                }
            }
            $hasTables = true;
            $step = 3;
            $notices[] = 'Tables créées et catégories importées.';
        } catch (Throwable $e) {
            $errors[] = 'Import impossible : ' . $e->getMessage();
            $step = 2;
        }
    }

    // --- étape 3 : le premier compte ---------------------------------------
    if ($action === 'account' && $pdo && $hasTables) {
        $username = trim((string) ($_POST['username'] ?? ''));
        $pass     = (string) ($_POST['pass'] ?? '');
        $confirm  = (string) ($_POST['confirm'] ?? '');

        if (!preg_match('/^[A-Za-z0-9._-]{3,80}$/', $username)) {
            $errors[] = 'Identifiant : 3 à 80 caractères (lettres, chiffres, . _ -).';
        }
        if (strlen($pass) < 12) {
            $errors[] = 'Mot de passe : 12 caractères minimum.';
        } elseif (preg_match('/^(?:[a-z]+|[0-9]+)$/i', $pass)) {
            $errors[] = 'Mot de passe trop simple : mélangez lettres, chiffres et symboles.';
        }
        if ($pass !== $confirm) {
            $errors[] = 'Les deux mots de passe diffèrent.';
        }

        if (!$errors) {
            $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)')
                ->execute([$username, password_hash($pass, PASSWORD_DEFAULT)]);
            $step = 4;
        } else {
            $step = 3;
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Installation — Alam Stores</title>
<link rel="stylesheet" href="../assets/css/main.min.css">
<style>
  body { background: var(--slate-50, #f6f7f9); }
  main { max-width: 640px; margin: 0 auto; padding: 40px 20px 80px; }
  .box { background: #fff; border: 1px solid var(--line); border-radius: var(--r-lg);
         padding: 26px; box-shadow: var(--sh-sm); margin-bottom: 20px; }
  h1 { font-size: 1.3rem; margin: 0 0 6px; }
  h2 { font-size: 1.05rem; margin: 0 0 4px; }
  .lede { color: var(--text-soft); font-size: .88rem; margin: 0 0 20px; }
  .steps { display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
  .steps span { font-size: .74rem; font-weight: 800; padding: 5px 12px; border-radius: 999px;
                background: var(--slate-100); color: var(--slate-600); }
  .steps span.is-on { background: var(--brand-600); color: #fff; }
  .steps span.is-done { background: #dcfce7; color: #166534; }
  label { display: block; font-size: .78rem; font-weight: 700; margin: 14px 0 5px; }
  input[type=text], input[type=password], input[type=number], select, textarea {
    width: 100%; padding: 10px 12px; border: 1px solid var(--line);
    border-radius: var(--r-sm); font: inherit; }
  .grid2 { display: grid; gap: 14px; grid-template-columns: 2fr 1fr; }
  .msg { padding: 12px 14px; border-radius: var(--r-sm); font-size: .85rem; margin-bottom: 12px; }
  .msg--bad { background: #fee2e2; color: #991b1b; }
  .msg--ok  { background: #dcfce7; color: #166534; }
  pre { background: var(--slate-900, #0f172a); color: #e2e8f0; padding: 16px; overflow-x: auto;
        border-radius: var(--r-sm); font-size: .78rem; line-height: 1.55; }
  .hint { font-size: .8rem; color: var(--text-mute); margin: 6px 0 0; }
</style>
</head>
<body>
<main>
  <div class="box">
    <h1>Installation du back-office</h1>
    <p class="lede">Trois étapes. Cette page se désactive toute seule dès qu'un
       compte existe — et vous propose alors de se supprimer.</p>
    <div class="steps">
      <span class="<?= $step > 1 ? 'is-done' : ($step === 1 ? 'is-on' : '') ?>">1. Base de données</span>
      <span class="<?= $step > 2 ? 'is-done' : ($step === 2 ? 'is-on' : '') ?>">2. Tables</span>
      <span class="<?= $step > 3 ? 'is-done' : ($step === 3 ? 'is-on' : '') ?>">3. Votre compte</span>
    </div>

    <?php foreach ($errors as $e): ?>
      <p class="msg msg--bad"><?= h($e) ?></p>
    <?php endforeach; ?>
    <?php foreach ($notices as $n): ?>
      <p class="msg msg--ok"><?= h($n) ?></p>
    <?php endforeach; ?>

    <?php if ($attempts): ?>
      <details style="margin-bottom:14px">
        <summary class="hint" style="cursor:pointer">Voir ce qui a été essayé
           (<?= count($attempts) ?> adresses)</summary>
        <pre><?php foreach ($attempts as $a) { echo h($a), "\n"; } ?></pre>
        <p class="hint">Si la même erreur revient partout — « Access denied »,
           « Unknown database » — le problème n'est pas l'adresse du serveur mais
           les identifiants, ou l'utilisateur qui n'est pas rattaché à la base.</p>
      </details>
    <?php endif; ?>

    <?php if ($step === 1): ?>
      <h2>1. Connexion à la base</h2>
      <p class="lede">Ces informations viennent de votre hébergeur (cPanel →
         Bases de données MySQL). Créez la base et l'utilisateur là-bas, puis
         recopiez-les ici.</p>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="config">
        <div class="grid2">
          <div><label for="host">Serveur</label>
            <input id="host" name="host" type="text" value="localhost"></div>
          <div><label for="port">Port</label>
            <input id="port" name="port" type="number" value="3306"></div>
        </div>
        <p class="hint">Laissez <code>localhost</code> si vous ne savez pas.
           Si ça ne répond pas, l'installateur essaie tout seul
           <code>127.0.0.1</code> puis les sockets MySQL habituels, et retient
           celui qui marche. Vous pouvez aussi coller directement un chemin de
           socket (il commence par <code>/</code>).</p>
        <label for="name">Nom de la base</label>
        <input id="name" name="name" type="text" required placeholder="ex. alamstor_site">
        <label for="user">Utilisateur</label>
        <input id="user" name="user" type="text" required placeholder="ex. alamstor_admin">
        <label for="password">Mot de passe</label>
        <input id="password" name="password" type="password">
        <label for="emails">Recevoir les devis par e-mail</label>
        <textarea id="emails" name="emails" rows="2"
        >contact@alamstores.ma, kassettebrahim.1997@gmail.com</textarea>
        <p class="hint">Une adresse par ligne ou séparées par des virgules.</p>

        <label for="rc_site">reCAPTCHA — clé du site</label>
        <input id="rc_site" name="rc_site" type="text"
               value="6LejfrItAAAAAMv9gSWoYpQYZfYUsJqQVACgbJqp">
        <label for="rc_secret">reCAPTCHA — clé secrète</label>
        <input id="rc_secret" name="rc_secret" type="password" autocomplete="off">
        <label for="rc_version">Type de reCAPTCHA</label>
        <select id="rc_version" name="rc_version">
          <option value="v2">v2 — case « Je ne suis pas un robot »</option>
          <option value="v3">v3 — invisible, par score</option>
        </select>
        <p class="hint">Les deux clés viennent de
           <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener">google.com/recaptcha/admin</a>.
           Laissez la clé secrète vide pour installer sans protection anti-robot
           et l'ajouter plus tard dans <code>api/config.php</code>.</p>

        <p style="margin-top:20px">
          <button class="btn btn--primary" type="submit">Tester et enregistrer</button></p>
      </form>

      <?php if ($configSource !== null): ?>
        <h2 style="margin-top:26px">Le fichier à déposer</h2>
        <p class="lede">Téléchargez-le, déposez-le dans le dossier <code>api/</code>
           de votre serveur avec FileZilla, puis rechargez cette page.</p>
        <form method="post" style="margin-bottom:16px">
          <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
          <input type="hidden" name="action" value="download">
          <input type="hidden" name="source" value="<?= h($configSource) ?>">
          <button class="btn btn--primary" type="submit">Télécharger config.php</button>
        </form>
        <p class="hint">Autre solution, si vous préférez&nbsp;: dans FileZilla,
           clic droit sur le dossier <code>api</code> → <strong>Droits d'accès au
           fichier</strong> → mettez <code>755</code>, puis relancez cette page.
           Elle écrira le fichier elle-même.</p>
        <details style="margin-top:14px">
          <summary class="hint" style="cursor:pointer">Voir le contenu du fichier</summary>
          <pre><?= h($configSource) ?></pre>
        </details>
      <?php endif; ?>

    <?php elseif ($step === 2): ?>
      <h2>2. Créer les tables</h2>
      <p class="lede">Importe <code>db/schema.sql</code> puis
         <code>db/seed_categories.sql</code>. Les instructions sont réexécutables :
         relancer n'efface aucune donnée.</p>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="schema">
        <button class="btn btn--primary" type="submit">Créer les tables</button>
      </form>

    <?php elseif ($step === 3): ?>
      <h2>3. Votre compte</h2>
      <p class="lede">Le mot de passe n'est jamais enregistré tel quel, seulement
         son empreinte. 12 caractères minimum.</p>
      <form method="post" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="account">
        <label for="username">Identifiant</label>
        <input id="username" name="username" type="text" required autocomplete="username">
        <label for="pass">Mot de passe</label>
        <input id="pass" name="pass" type="password" required autocomplete="new-password">
        <label for="confirm">Confirmation</label>
        <input id="confirm" name="confirm" type="password" required autocomplete="new-password">
        <p style="margin-top:20px">
          <button class="btn btn--primary" type="submit">Créer le compte</button></p>
      </form>

    <?php else: ?>
      <h2>Terminé</h2>
      <p class="lede">Le compte est créé et le back-office est prêt.</p>
      <p class="msg msg--bad"><strong>Dernière étape, importante :</strong>
         supprimez <code>admin/setup.php</code> de votre serveur.</p>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
        <button class="btn btn--primary" type="submit" name="selfdestruct" value="1">
          Supprimer setup.php et ouvrir le back-office</button>
      </form>
    <?php endif; ?>
  </div>
</main>
</body>
</html>
