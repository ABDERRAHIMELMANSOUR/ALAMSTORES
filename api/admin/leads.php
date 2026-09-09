<?php
declare(strict_types=1);

/**
 * Demandes de devis reçues — lecture, filtres, export CSV, suppression.
 *
 *   GET    api/admin/leads.php?search=&statut=&category=&page=1
 *   GET    api/admin/leads.php?format=csv        → export Excel
 *   PATCH  api/admin/leads.php?id=12             → marquer lu / non lu
 *   DELETE api/admin/leads.php?id=12
 */

require dirname(__DIR__) . '/bootstrap.php';

$method = require_method('GET', 'PATCH', 'DELETE');
send_security_headers();
require_login();

$pdo = db();

const LEAD_FIELDS = [
    'created_at' => 'Date', 'statut' => 'Statut', 'company' => 'Entreprise',
    'category'   => 'Catégorie', 'firstname' => 'Prénom', 'lastname' => 'Nom',
    'email'      => 'E-mail', 'phone' => 'Téléphone', 'address' => 'Adresse',
    'zip'        => 'Code postal', 'city' => 'Ville', 'country' => 'Pays',
    'message'    => 'Message',
];

/** Clause WHERE commune à la liste et à l'export. */
function lead_filter(): array
{
    $where = [];
    $args  = [];

    $search = clean_text($_GET['search'] ?? '', 120);
    if ($search !== '') {
        $where[] = '(firstname LIKE :q OR lastname LIKE :q OR company LIKE :q
                     OR email LIKE :q OR phone LIKE :q OR city LIKE :q OR message LIKE :q)';
        // Les jokers saisis par l'utilisateur sont neutralisés : une recherche
        // sur « 100 % » ne doit pas balayer toute la table.
        $args[':q'] = '%' . addcslashes($search, '%_\\') . '%';
    }
    foreach (['statut' => 'statut', 'category' => 'category'] as $param => $column) {
        $value = clean_text($_GET[$param] ?? '', 120);
        if ($value !== '') {
            $where[] = "$column = :$param";
            $args[":$param"] = $value;
        }
    }
    return [$where ? 'WHERE ' . implode(' AND ', $where) : '', $args];
}

// -------------------------------------------------------------- export CSV
if ($method === 'GET' && ($_GET['format'] ?? '') === 'csv') {
    [$where, $args] = lead_filter();
    $stmt = $pdo->prepare('SELECT ' . implode(', ', array_keys(LEAD_FIELDS)) .
                          " FROM leads $where ORDER BY created_at DESC");
    $stmt->execute($args);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="devis-alamstores-' . date('Y-m-d') . '.csv"');
    header('Cache-Control: no-store');

    $out = fopen('php://output', 'wb');
    fwrite($out, "\xEF\xBB\xBF");            // BOM : Excel lit les accents
    fputcsv($out, array_values(LEAD_FIELDS), ';');
    while ($row = $stmt->fetch()) {
        // Une cellule qui commence par = + - @ est interprétée comme une
        // formule par Excel. On la neutralise avant l'export.
        fputcsv($out, array_map(static function ($v): string {
            $v = (string) $v;
            return $v !== '' && strpos("=+-@\t\r", $v[0]) !== false ? "'" . $v : $v;
        }, $row), ';');
    }
    fclose($out);
    exit;
}

// ------------------------------------------------------------------- liste
if ($method === 'GET') {
    [$where, $args] = lead_filter();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM leads $where");
    $stmt->execute($args);
    $total = (int) $stmt->fetchColumn();

    $perPage = 50;
    $page    = max(1, (int) ($_GET['page'] ?? 1));
    $offset  = ($page - 1) * $perPage;

    // LIMIT/OFFSET interpolés depuis des entiers déjà contraints : aucune
    // valeur de l'utilisateur n'atteint la requête sous forme de texte.
    $stmt = $pdo->prepare('SELECT id, is_read, ' . implode(', ', array_keys(LEAD_FIELDS)) .
                          " FROM leads $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
    $stmt->execute($args);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$row) {
        $row['id']      = (int) $row['id'];
        $row['is_read'] = (bool) $row['is_read'];
    }
    unset($row);

    json_out([
        'leads'      => $rows,
        'total'      => $total,
        'page'       => $page,
        'pages'      => (int) ceil($total / $perPage) ?: 1,
        'unread'     => (int) $pdo->query('SELECT COUNT(*) FROM leads WHERE is_read = 0')->fetchColumn(),
        'statuts'    => $pdo->query("SELECT DISTINCT statut FROM leads WHERE statut <> '' ORDER BY statut")
                             ->fetchAll(PDO::FETCH_COLUMN),
        'categories' => $pdo->query("SELECT DISTINCT category FROM leads WHERE category <> '' ORDER BY category")
                             ->fetchAll(PDO::FETCH_COLUMN),
        'csrf'       => csrf_token(),
    ]);
}

// ----------------------------------------------------------- lu / supprimé
$body = json_body();
require_csrf($body);

$id = (int) ($_GET['id'] ?? $body['id'] ?? 0);
if ($id <= 0) {
    json_out(['error' => 'Identifiant manquant.'], 400);
}

if ($method === 'PATCH') {
    $stmt = $pdo->prepare('UPDATE leads SET is_read = ? WHERE id = ?');
    $stmt->execute([!empty($body['is_read']) ? 1 : 0, $id]);
    json_out(['ok' => true, 'id' => $id]);
}

$pdo->prepare('DELETE FROM leads WHERE id = ?')->execute([$id]);
json_out(['ok' => true, 'deleted' => $id]);
