<?php
declare(strict_types=1);

/**
 * CRUD du catalogue — réservé au back-office authentifié.
 *
 *   GET    api/admin/products.php            → tout, brouillons compris
 *   POST   api/admin/products.php            → création
 *   PUT    api/admin/products.php?id=12      → mise à jour
 *   DELETE api/admin/products.php?id=12      → suppression
 *
 * Obligatoires : titre, description, catégorie et au moins une image.
 * Facultatifs  : vidéo YouTube, fiches techniques, caractéristiques,
 *                rattachement à un produit parent (sous-produit).
 */

require dirname(__DIR__) . '/bootstrap.php';

$method = require_method('GET', 'POST', 'PUT', 'DELETE');
send_security_headers();
require_login();

$pdo = db();

// ------------------------------------------------------------------ lecture
if ($method === 'GET') {
    $rows = $pdo->query(
        'SELECT p.id, p.slug, p.title, p.description, p.video_url, p.is_published,
                p.position, p.parent_id, p.category_id, c.slug AS category,
                c.name AS category_name
           FROM products p
           JOIN categories c ON c.id = p.category_id
          ORDER BY c.position, p.position, p.id')->fetchAll();

    $byId = [];
    foreach ($rows as $r) {
        $r['id']           = (int) $r['id'];
        $r['category_id']  = (int) $r['category_id'];
        $r['parent_id']    = $r['parent_id'] !== null ? (int) $r['parent_id'] : null;
        $r['is_published'] = (bool) $r['is_published'];
        $r['images'] = $r['docs'] = $r['specs'] = [];
        $byId[$r['id']] = $r;
    }

    if ($byId) {
        $ids = array_keys($byId);
        $in  = implode(',', array_fill(0, count($ids), '?'));
        foreach ([
            'images' => "SELECT id, product_id, path, alt FROM product_images WHERE product_id IN ($in) ORDER BY position, id",
            'docs'   => "SELECT id, product_id, path, label, size_bytes FROM product_docs WHERE product_id IN ($in) ORDER BY id",
            'specs'  => "SELECT id, product_id, label, value FROM product_specs WHERE product_id IN ($in) ORDER BY position, id",
        ] as $key => $sql) {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($ids);
            foreach ($stmt as $row) {
                $pid = (int) $row['product_id'];
                unset($row['product_id']);
                $byId[$pid][$key][] = $row;
            }
        }
    }

    $categories = $pdo->query(
        'SELECT c.id, c.slug, c.name, c.family, p.name AS parent_name
           FROM categories c
      LEFT JOIN categories p ON p.id = c.parent_id
          ORDER BY c.family, c.position')->fetchAll();

    json_out([
        'products'   => array_values($byId),
        'categories' => $categories,
        'csrf'       => csrf_token(),
    ]);
}

// ------------------------------------------------------- écriture : contrôles
$body = json_body();
require_csrf($body);

/** Chemin d'un média accepté : uniquement sous les dossiers du site. */
function safe_media_path($value): ?string
{
    $p = clean_text($value, 300);
    if ($p === '' || strpos($p, '..') !== false || $p[0] === '/' || preg_match('~^[a-z]+://~i', $p)) {
        return null;
    }
    if (!preg_match('~^assets/(uploads|images|docs)/[A-Za-z0-9._/-]+$~', $p)) {
        return null;
    }
    return $p;
}

function validate(array $body, PDO $pdo, ?int $selfId): array
{
    $errors = [];

    $title = clean_text($body['title'] ?? '', 200);
    $desc  = clean_multiline($body['description'] ?? '', 5000);
    if ($title === '') {
        $errors['title'] = 'Le titre est obligatoire.';
    }
    if ($desc === '') {
        $errors['description'] = 'La description est obligatoire.';
    }

    $categoryId = (int) ($body['category_id'] ?? 0);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE id = ?');
    $stmt->execute([$categoryId]);
    if (!$stmt->fetchColumn()) {
        $errors['category_id'] = 'Choisissez une catégorie.';
    }

    // Au moins une image : c'est la seule pièce média obligatoire.
    $images = [];
    foreach ((array) ($body['images'] ?? []) as $img) {
        $path = safe_media_path(is_array($img) ? ($img['path'] ?? '') : $img);
        if ($path !== null) {
            $images[] = ['path' => $path,
                         'alt'  => clean_text(is_array($img) ? ($img['alt'] ?? '') : '', 200)];
        }
    }
    if (!$images) {
        $errors['images'] = 'Ajoutez au moins une photo.';
    }

    // Vidéo : facultative, mais si elle est renseignée elle doit être une
    // vraie URL YouTube — sinon la page ne saurait pas quoi ouvrir.
    $videoRaw = clean_text($body['video_url'] ?? '', 500);
    $videoId  = $videoRaw !== '' ? youtube_id($videoRaw) : null;
    if ($videoRaw !== '' && $videoId === null) {
        $errors['video_url'] = 'Lien YouTube non reconnu (collez l’URL de la vidéo).';
    }

    // Fiches techniques : facultatives.
    $docs = [];
    foreach ((array) ($body['docs'] ?? []) as $doc) {
        $path = safe_media_path(is_array($doc) ? ($doc['path'] ?? '') : $doc);
        if ($path !== null) {
            $docs[] = [
                'path'  => $path,
                'label' => clean_text(is_array($doc) ? ($doc['label'] ?? '') : '', 200)
                           ?: 'Fiche technique (PDF)',
                'size'  => (int) (is_array($doc) ? ($doc['size_bytes'] ?? 0) : 0),
            ];
        }
    }

    $specs = [];
    foreach ((array) ($body['specs'] ?? []) as $sp) {
        $label = clean_text($sp['label'] ?? '', 160);
        $value = clean_text($sp['value'] ?? '', 300);
        if ($label !== '' || $value !== '') {
            $specs[] = ['label' => $label, 'value' => $value];
        }
    }

    // Sous-produit : le parent doit exister, être un produit principal, et ne
    // pas être le produit lui-même.
    $parentId = isset($body['parent_id']) && $body['parent_id'] !== '' && $body['parent_id'] !== null
        ? (int) $body['parent_id'] : null;
    if ($parentId !== null) {
        if ($selfId !== null && $parentId === $selfId) {
            $errors['parent_id'] = 'Un produit ne peut pas être son propre parent.';
        } else {
            $stmt = $pdo->prepare('SELECT parent_id FROM products WHERE id = ?');
            $stmt->execute([$parentId]);
            $row = $stmt->fetch();
            if (!$row) {
                $errors['parent_id'] = 'Produit parent introuvable.';
            } elseif ($row['parent_id'] !== null) {
                $errors['parent_id'] = 'Un sous-produit ne peut pas en contenir un autre.';
            }
        }
    }

    return [$errors, [
        'title'        => $title,
        'description'  => $desc,
        'category_id'  => $categoryId,
        'parent_id'    => $parentId,
        'video_url'    => $videoId !== null ? 'https://www.youtube.com/watch?v=' . $videoId : null,
        'is_published' => !empty($body['is_published']) ? 1 : 0,
        'position'     => max(0, min(9999, (int) ($body['position'] ?? 0))),
        'images'       => $images,
        'docs'         => $docs,
        'specs'        => $specs,
    ]];
}

/** Slug unique, dérivé du titre. */
function unique_slug(PDO $pdo, string $title, ?int $selfId): string
{
    $base = slugify($title);
    $slug = $base;
    $n    = 2;
    $stmt = $pdo->prepare('SELECT id FROM products WHERE slug = ? AND id <> ?');
    while (true) {
        $stmt->execute([$slug, $selfId ?? 0]);
        if (!$stmt->fetch()) {
            return $slug;
        }
        $slug = $base . '-' . $n++;
        if ($n > 200) {
            return $base . '-' . bin2hex(random_bytes(3));
        }
    }
}

/** Réécrit les tables liées d'un produit. */
function write_children(PDO $pdo, int $id, array $data): void
{
    $pdo->prepare('DELETE FROM product_images WHERE product_id = ?')->execute([$id]);
    $stmt = $pdo->prepare('INSERT INTO product_images (product_id, path, alt, position)
                           VALUES (?, ?, ?, ?)');
    foreach ($data['images'] as $i => $img) {
        $stmt->execute([$id, $img['path'], $img['alt'], $i]);
    }

    $pdo->prepare('DELETE FROM product_docs WHERE product_id = ?')->execute([$id]);
    $stmt = $pdo->prepare('INSERT INTO product_docs (product_id, path, label, size_bytes)
                           VALUES (?, ?, ?, ?)');
    foreach ($data['docs'] as $doc) {
        $stmt->execute([$id, $doc['path'], $doc['label'], $doc['size']]);
    }

    $pdo->prepare('DELETE FROM product_specs WHERE product_id = ?')->execute([$id]);
    $stmt = $pdo->prepare('INSERT INTO product_specs (product_id, label, value, position)
                           VALUES (?, ?, ?, ?)');
    foreach ($data['specs'] as $i => $sp) {
        $stmt->execute([$id, $sp['label'], $sp['value'], $i]);
    }
}

// ----------------------------------------------------------------- création
if ($method === 'POST') {
    [$errors, $data] = validate($body, $pdo, null);
    if ($errors) {
        json_out(['error' => 'Formulaire incomplet.', 'fields' => $errors], 422);
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare(
            'INSERT INTO products (category_id, parent_id, slug, title, description,
                                   video_url, is_published, position)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['category_id'], $data['parent_id'],
            unique_slug($pdo, $data['title'], null),
            $data['title'], $data['description'], $data['video_url'],
            $data['is_published'], $data['position'],
        ]);
        $id = (int) $pdo->lastInsertId();
        write_children($pdo, $id, $data);
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
    json_out(['ok' => true, 'id' => $id], 201);
}

// --------------------------------------------------------------- mise à jour
$id = (int) ($_GET['id'] ?? $body['id'] ?? 0);
if ($id <= 0) {
    json_out(['error' => 'Identifiant de produit manquant.'], 400);
}
$stmt = $pdo->prepare('SELECT id FROM products WHERE id = ?');
$stmt->execute([$id]);
if (!$stmt->fetch()) {
    json_out(['error' => 'Produit introuvable.'], 404);
}

if ($method === 'PUT') {
    [$errors, $data] = validate($body, $pdo, $id);
    if ($errors) {
        json_out(['error' => 'Formulaire incomplet.', 'fields' => $errors], 422);
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare(
            'UPDATE products SET category_id = ?, parent_id = ?, slug = ?, title = ?,
                    description = ?, video_url = ?, is_published = ?, position = ?
              WHERE id = ?');
        $stmt->execute([
            $data['category_id'], $data['parent_id'],
            unique_slug($pdo, $data['title'], $id),
            $data['title'], $data['description'], $data['video_url'],
            $data['is_published'], $data['position'], $id,
        ]);
        write_children($pdo, $id, $data);
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
    json_out(['ok' => true, 'id' => $id]);
}

// --------------------------------------------------------------- suppression
// Les fichiers téléversés partent avec la fiche, mais seulement s'ils sont
// sous assets/uploads/ et qu'aucun autre produit ne les référence.
$paths = [];
foreach (['product_images', 'product_docs'] as $table) {
    $stmt = $pdo->prepare("SELECT path FROM $table WHERE product_id IN
                           (SELECT id FROM products WHERE id = ? OR parent_id = ?)");
    $stmt->execute([$id, $id]);
    $paths = array_merge($paths, $stmt->fetchAll(PDO::FETCH_COLUMN));
}

$pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);   // cascade

$root = dirname(__DIR__, 2);
foreach (array_unique($paths) as $path) {
    if (strpos($path, 'assets/uploads/') !== 0) {
        continue;                                  // média d'origine du site
    }
    foreach (['product_images', 'product_docs'] as $table) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE path = ?");
        $stmt->execute([$path]);
        if ((int) $stmt->fetchColumn() > 0) {
            continue 2;                            // encore utilisé ailleurs
        }
    }
    $full = $root . '/' . $path;
    if (is_file($full) && strpos(realpath($full) ?: '', $root . '/assets/uploads/') === 0) {
        @unlink($full);
    }
}

json_out(['ok' => true, 'deleted' => $id]);
