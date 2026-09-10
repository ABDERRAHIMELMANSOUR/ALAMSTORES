<?php
declare(strict_types=1);

/**
 * Catalogue public, en lecture seule.
 *
 *   GET api/catalog.php                → tout le catalogue publié
 *   GET api/catalog.php?category=slug  → une seule catégorie
 *
 * C'est ce que les pages du site appellent pour afficher les produits saisis
 * dans le back-office. Aucune donnée privée n'est exposée : ni les brouillons
 * (is_published = 0), ni les leads, ni les comptes.
 */

require __DIR__ . '/bootstrap.php';

require_method('GET', 'HEAD');
send_security_headers();
apply_cors();

$wanted = isset($_GET['category']) ? clean_text($_GET['category'], 120) : '';
if ($wanted !== '' && !preg_match('/^[a-z0-9-]{1,120}$/', $wanted)) {
    json_out(['error' => 'Catégorie invalide.'], 400);
}

// Site pas encore installé : un catalogue vide vaut mieux qu'une erreur.
if (!config_installed() || !cfg('db')) {
    json_out(['generated' => gmdate('c'), 'categories' => [], 'products' => []]);
}

try {
    $pdo = db();
} catch (Throwable $e) {
    error_log('[alamstores] catalogue : base injoignable — ' . $e->getMessage());
    json_out(['generated' => gmdate('c'), 'categories' => [], 'products' => []]);
}

// -------------------------------------------------------------- catégories
$categories = [];
foreach ($pdo->query('SELECT c.id, c.slug, c.name, c.family, p.slug AS parent
                        FROM categories c
                   LEFT JOIN categories p ON p.id = c.parent_id
                    ORDER BY c.position') as $row) {
    $categories[$row['slug']] = [
        'name'   => $row['name'],
        'family' => $row['family'],
        'parent' => $row['parent'],
    ];
}

// ----------------------------------------------------------------- produits
$sql = 'SELECT pr.id, pr.slug, pr.title, pr.description, pr.video_url,
               pr.parent_id, pr.position, c.slug AS category
          FROM products pr
          JOIN categories c ON c.id = pr.category_id
         WHERE pr.is_published = 1';
$args = [];
if ($wanted !== '') {
    $sql   .= ' AND c.slug = ?';
    $args[] = $wanted;
}
$sql .= ' ORDER BY pr.position, pr.id';

$stmt = $pdo->prepare($sql);
$stmt->execute($args);
$rows = $stmt->fetchAll();

if (!$rows) {
    json_out(['generated' => gmdate('c'), 'categories' => $categories, 'products' => []]);
}

$ids = array_column($rows, 'id');
$in  = implode(',', array_fill(0, count($ids), '?'));

/** Charge une table liée et la regroupe par product_id. */
$grouped = static function (PDO $pdo, string $sql, array $ids): array {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);
    $out = [];
    foreach ($stmt as $r) {
        $pid = (int) $r['product_id'];
        unset($r['product_id']);
        $out[$pid][] = $r;
    }
    return $out;
};

$images = $grouped($pdo, "SELECT product_id, path, alt FROM product_images
                           WHERE product_id IN ($in) ORDER BY position, id", $ids);
$docs   = $grouped($pdo, "SELECT product_id, path, label, size_bytes FROM product_docs
                           WHERE product_id IN ($in) ORDER BY id", $ids);
$specs  = $grouped($pdo, "SELECT product_id, label, value FROM product_specs
                           WHERE product_id IN ($in) ORDER BY position, id", $ids);

// --------------------------------------------------- mise en forme des fiches
$byId = [];
foreach ($rows as $r) {
    $id  = (int) $r['id'];
    $vid = $r['video_url'] ? youtube_id((string) $r['video_url']) : null;

    $byId[$id] = [
        'id'          => $id,
        'slug'        => $r['slug'],
        'title'       => $r['title'],
        'description' => $r['description'],
        'category'    => $r['category'],
        // Seul l'identifiant est publié : la page construit l'URL elle-même,
        // ce qui interdit toute injection d'URL arbitraire dans l'<iframe>.
        'video'       => $vid ? ['id' => $vid, 'thumb' => "https://i.ytimg.com/vi/$vid/hqdefault.jpg"] : null,
        'images'      => $images[$id] ?? [],
        'docs'        => $docs[$id] ?? [],
        'specs'       => $specs[$id] ?? [],
        'variants'    => [],
        '_parent'     => $r['parent_id'] !== null ? (int) $r['parent_id'] : null,
    ];
}

// Les sous-produits se rangent sous leur parent, sur un seul niveau. Un
// sous-produit dont le parent est dépublié remonte au niveau principal plutôt
// que de disparaître du site.
$attached = [];
foreach ($byId as $id => $prod) {
    $parent = $prod['_parent'];
    if ($parent !== null && isset($byId[$parent]) && $byId[$parent]['_parent'] === null) {
        unset($prod['_parent'], $prod['variants']);
        $byId[$parent]['variants'][] = $prod;
        $attached[$id] = true;
    }
}

$catalogue = [];
foreach ($byId as $id => $prod) {
    if (isset($attached[$id])) {
        continue;
    }
    unset($prod['_parent']);
    $catalogue[$prod['category']][] = $prod;
}

$payload = [
    'generated'  => gmdate('c'),
    'categories' => $categories,
    'products'   => $catalogue,
];

// Réponse courte en cache, revalidée par ETag : les pages n'attendent pas la
// base à chaque visite, mais une mise à jour est visible en moins d'une minute.
$body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$etag = '"' . md5((string) $body) . '"';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=60, stale-while-revalidate=300');
header('ETag: ' . $etag);
if (trim((string) ($_SERVER['HTTP_IF_NONE_MATCH'] ?? '')) === $etag) {
    http_response_code(304);
    exit;
}
echo $body;
