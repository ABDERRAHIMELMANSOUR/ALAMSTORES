<?php
declare(strict_types=1);

/**
 * Gestion de l'arbre des catégories — réservé au back-office authentifié.
 *
 *   GET    api/admin/categories.php        → l'arbre, avec le nombre de produits
 *   POST   api/admin/categories.php        → création
 *   PUT    api/admin/categories.php?id=7   → renommer, afficher/masquer, déplacer
 *   DELETE api/admin/categories.php?id=7   → suppression (refusée si non vide,
 *                                             sauf ?force=1)
 *
 * Deux grandes familles, Intérieur et Extérieur, chacune avec ses
 * sous-catégories. `family` n'est jamais saisie : elle est héritée du parent,
 * ce qui garantit qu'une sous-catégorie reste du bon côté du menu.
 */

require dirname(__DIR__) . '/bootstrap.php';

$method = require_method('GET', 'POST', 'PUT', 'DELETE');
send_security_headers();
require_login();

$pdo = db();

const MAX_DEPTH = 3;   // Intérieur > Stores Enrouleurs > Store Enrouleur Screen

/** La catégorie, ou null. */
function category(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/** Profondeur d'une catégorie : 1 pour une famille, 2 pour son enfant… */
function depth(PDO $pdo, ?int $id): int
{
    $n = 0;
    while ($id !== null && $n < 10) {
        $row = category($pdo, $id);
        if (!$row) {
            break;
        }
        $n++;
        $id = $row['parent_id'] !== null ? (int) $row['parent_id'] : null;
    }
    return $n;
}

/** Vrai si $candidate est $id lui-même ou l'un de ses descendants. */
function is_descendant(PDO $pdo, int $candidate, int $id): bool
{
    $cursor = $candidate;
    for ($i = 0; $i < 10 && $cursor !== 0; $i++) {
        if ($cursor === $id) {
            return true;
        }
        $row = category($pdo, $cursor);
        $cursor = ($row && $row['parent_id'] !== null) ? (int) $row['parent_id'] : 0;
    }
    return false;
}

/** Slug unique, dérivé du nom. */
function unique_category_slug(PDO $pdo, string $name, ?int $selfId): string
{
    $base = slugify($name, 'categorie');
    $slug = $base;
    $stmt = $pdo->prepare('SELECT id FROM categories WHERE slug = ? AND id <> ?');
    for ($n = 2; $n < 200; $n++) {
        $stmt->execute([$slug, $selfId ?? 0]);
        if (!$stmt->fetch()) {
            return $slug;
        }
        $slug = $base . '-' . $n;
    }
    return $base . '-' . bin2hex(random_bytes(3));
}

/** Applique une famille à toute la descendance d'une catégorie. */
function cascade_family(PDO $pdo, int $parentId, string $family, int $level = 0): void
{
    if ($level >= MAX_DEPTH) {
        return;
    }
    $stmt = $pdo->prepare('SELECT id FROM categories WHERE parent_id = ?');
    $stmt->execute([$parentId]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!$ids) {
        return;
    }
    $update = $pdo->prepare('UPDATE categories SET family = ? WHERE id = ?');
    foreach ($ids as $childId) {
        $update->execute([$family, $childId]);
        cascade_family($pdo, (int) $childId, $family, $level + 1);
    }
}

// ------------------------------------------------------------------ lecture
if ($method === 'GET') {
    $rows = $pdo->query(
        'SELECT c.id, c.slug, c.name, c.family, c.parent_id, c.position, c.is_visible,
                (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS product_count
           FROM categories c
          ORDER BY c.position, c.id')->fetchAll();

    foreach ($rows as &$row) {
        $row['id']            = (int) $row['id'];
        $row['parent_id']     = $row['parent_id'] !== null ? (int) $row['parent_id'] : null;
        $row['position']      = (int) $row['position'];
        $row['is_visible']    = (bool) $row['is_visible'];
        $row['product_count'] = (int) $row['product_count'];
        // Une catégorie générée par le site a une page à elle ; une catégorie
        // créée ici est servie par categorie.php.
        $row['has_page'] = is_file(dirname(__DIR__, 2) . '/' . $row['slug'] . '.php');
    }
    unset($row);

    json_out(['categories' => $rows, 'csrf' => csrf_token()]);
}

// ------------------------------------------------------- écriture : contrôles
$body = json_body();
require_csrf($body);

// ---------------------------------------------------------------- création
if ($method === 'POST') {
    $name = clean_text($body['name'] ?? '', 180);
    if ($name === '') {
        json_out(['error' => 'Formulaire incomplet.',
                  'fields' => ['name' => 'Le nom est obligatoire.']], 422);
    }

    $parentId = isset($body['parent_id']) && $body['parent_id'] !== '' && $body['parent_id'] !== null
        ? (int) $body['parent_id'] : null;
    $family = 'autre';

    if ($parentId !== null) {
        $parent = category($pdo, $parentId);
        if (!$parent) {
            json_out(['error' => 'Formulaire incomplet.',
                      'fields' => ['parent_id' => 'Catégorie parente introuvable.']], 422);
        }
        if (depth($pdo, $parentId) >= MAX_DEPTH) {
            json_out(['error' => 'Formulaire incomplet.',
                      'fields' => ['parent_id' => 'Trois niveaux au maximum.']], 422);
        }
        $family = $parent['family'];
    } else {
        // Une catégorie sans parent doit choisir son côté du menu.
        $family = in_array($body['family'] ?? '', ['interieur', 'exterieur'], true)
            ? $body['family'] : 'autre';
    }

    $stmt = $pdo->prepare('SELECT COALESCE(MAX(position), 0) + 1 FROM categories');
    $stmt->execute();
    $position = (int) $stmt->fetchColumn();

    $stmt = $pdo->prepare(
        'INSERT INTO categories (slug, name, parent_id, family, position, is_visible)
         VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([unique_category_slug($pdo, $name, null), $name, $parentId,
                    $family, $position, empty($body['is_visible']) ? 0 : 1]);

    json_out(['ok' => true, 'id' => (int) $pdo->lastInsertId()], 201);
}

// ---------------------------------------------------------- mise à jour ----
$id = (int) ($_GET['id'] ?? $body['id'] ?? 0);
$current = $id > 0 ? category($pdo, $id) : null;
if (!$current) {
    json_out(['error' => 'Catégorie introuvable.'], 404);
}

if ($method === 'PUT') {
    $fields = [];
    $args   = [];

    if (array_key_exists('name', $body)) {
        $name = clean_text($body['name'], 180);
        if ($name === '') {
            json_out(['error' => 'Formulaire incomplet.',
                      'fields' => ['name' => 'Le nom est obligatoire.']], 422);
        }
        $fields[] = 'name = ?';
        $args[]   = $name;
        // Le slug ne bouge pas : c'est l'adresse de la page, et la changer
        // casserait les liens déjà indexés.
    }

    if (array_key_exists('is_visible', $body)) {
        $fields[] = 'is_visible = ?';
        $args[]   = empty($body['is_visible']) ? 0 : 1;
    }

    if (array_key_exists('position', $body)) {
        $fields[] = 'position = ?';
        $args[]   = max(0, min(9999, (int) $body['position']));
    }

    if (array_key_exists('parent_id', $body)) {
        $parentId = ($body['parent_id'] === '' || $body['parent_id'] === null)
            ? null : (int) $body['parent_id'];

        if ($parentId !== null) {
            $parent = category($pdo, $parentId);
            if (!$parent) {
                json_out(['error' => 'Formulaire incomplet.',
                          'fields' => ['parent_id' => 'Catégorie parente introuvable.']], 422);
            }
            // Se rattacher à soi-même ou à l'un de ses descendants détacherait
            // toute la branche de l'arbre.
            if (is_descendant($pdo, $parentId, $id)) {
                json_out(['error' => 'Formulaire incomplet.',
                          'fields' => ['parent_id' => 'Une catégorie ne peut pas être rangée sous elle-même.']], 422);
            }
            if (depth($pdo, $parentId) >= MAX_DEPTH) {
                json_out(['error' => 'Formulaire incomplet.',
                          'fields' => ['parent_id' => 'Trois niveaux au maximum.']], 422);
            }
            $fields[] = 'parent_id = ?';
            $args[]   = $parentId;
            $fields[] = 'family = ?';
            $args[]   = $parent['family'];
        } else {
            $fields[] = 'parent_id = NULL';
            if (in_array($body['family'] ?? '', ['interieur', 'exterieur', 'autre'], true)) {
                $fields[] = 'family = ?';
                $args[]   = $body['family'];
            }
        }
    } elseif (array_key_exists('family', $body)
              && in_array($body['family'], ['interieur', 'exterieur', 'autre'], true)
              && $current['parent_id'] === null) {
        $fields[] = 'family = ?';
        $args[]   = $body['family'];
    }

    if (!$fields) {
        json_out(['ok' => true, 'id' => $id]);
    }

    $args[] = $id;
    $pdo->prepare('UPDATE categories SET ' . implode(', ', $fields) . ' WHERE id = ?')
        ->execute($args);

    // Une famille qui change entraîne toute sa descendance avec elle : une
    // sous-catégorie déplacée côté Extérieur doit sortir du menu Intérieur.
    $stmt = $pdo->prepare('SELECT family FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    cascade_family($pdo, $id, (string) $stmt->fetchColumn());

    json_out(['ok' => true, 'id' => $id]);
}

// ---------------------------------------------------------- suppression ----
$stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
$stmt->execute([$id]);
$products = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE parent_id = ?');
$stmt->execute([$id]);
$children = (int) $stmt->fetchColumn();

if (($products || $children) && empty($_GET['force'])) {
    json_out([
        'error' => 'Cette catégorie n’est pas vide.',
        'needs_force' => true,
        'products'    => $products,
        'children'    => $children,
    ], 409);
}

// La cascade emporte les sous-catégories, leurs produits et leurs médias.
$pdo->prepare('DELETE FROM categories WHERE id = ?')->execute([$id]);

json_out(['ok' => true, 'deleted' => $id]);
