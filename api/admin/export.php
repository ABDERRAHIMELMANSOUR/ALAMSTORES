<?php
declare(strict_types=1);

/**
 * Export du catalogue au format tools/data/products.json.
 *
 *   GET api/admin/export.php            → téléchargement du fichier
 *   GET api/admin/export.php?raw=1      → même contenu, affiché dans le navigateur
 *
 * C'est le pont entre la base et le générateur statique : on remplace
 * tools/data/products.json par ce fichier, on relance
 * `python3 tools/build_pages.py`, et les produits sont figés dans le HTML
 * (utile pour le référencement et pour servir le site même si PHP tombe).
 */

require dirname(__DIR__) . '/bootstrap.php';

require_method('GET');
send_security_headers();
require_installed();
require_login();

$pdo = db();

$rows = $pdo->query(
    'SELECT p.id, p.title, p.description, p.video_url, p.parent_id, p.position,
            c.slug AS category
       FROM products p
       JOIN categories c ON c.id = p.category_id
      WHERE p.is_published = 1
      ORDER BY c.position, p.position, p.id')->fetchAll();

$byId = [];
foreach ($rows as $r) {
    $byId[(int) $r['id']] = $r;
}

/** Tables liées d'un produit, dans l'ordre d'affichage. */
$children = static function (PDO $pdo, string $sql, int $id): array {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchAll();
};

$fiche = static function (PDO $pdo, array $r) use ($children): array {
    $id    = (int) $r['id'];
    $imgs  = $children($pdo, 'SELECT path FROM product_images WHERE product_id = ? ORDER BY position, id', $id);
    $docs  = $children($pdo, 'SELECT path, label FROM product_docs WHERE product_id = ? ORDER BY id', $id);
    $specs = $children($pdo, 'SELECT label, value FROM product_specs WHERE product_id = ? ORDER BY position, id', $id);

    return [
        'id'          => 'db-' . $id,
        'title'       => $r['title'],
        'category'    => $r['category'],
        'description' => $r['description'],
        'photos'      => array_column($imgs, 'path'),
        'video'       => (string) ($r['video_url'] ?? ''),
        'datasheet'   => [
            'file'  => $docs[0]['path'] ?? '',
            'label' => $docs[0]['label'] ?? 'Fiche technique (PDF)',
        ],
        'specs'       => $specs,
        'variants'    => [],
    ];
};

$out = [];
foreach ($byId as $id => $r) {
    if ($r['parent_id'] !== null && isset($byId[(int) $r['parent_id']])) {
        continue;                              // rattaché plus bas
    }
    $item = $fiche($pdo, $r);
    foreach ($byId as $child) {
        if ((int) ($child['parent_id'] ?? 0) === $id) {
            $sub = $fiche($pdo, $child);
            unset($sub['variants'], $sub['category']);
            $item['variants'][] = $sub;
        }
    }
    $out[] = $item;
}

$json = json_encode([
    '$comment' => 'Généré par api/admin/export.php depuis la base. '
                . 'Remplacez tools/data/products.json puis lancez : python3 tools/build_pages.py',
    'version'    => 2,
    'exported_at' => gmdate('c'),
    'products'   => $out,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if (empty($_GET['raw'])) {
    header('Content-Disposition: attachment; filename="products.json"');
}
echo $json;
