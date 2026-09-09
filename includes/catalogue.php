<?php
declare(strict_types=1);

/**
 * Rendu du catalogue par le serveur.
 *
 * Les fiches viennent de la base — donc de ce qui a été saisi dans le
 * back-office — et sont écrites directement dans le HTML : un moteur de
 * recherche les voit sans exécuter de JavaScript, et publier un produit ne
 * demande plus de regénérer le site.
 *
 * Si la base est injoignable, on retombe sur les fiches figées à la
 * génération (includes/catalogue-static.php) plutôt que d'afficher un trou.
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/icons.php';

/** Identifiant YouTube d'une URL, ou null. Miroir de youtube_id() côté Python. */
function alam_youtube_id(?string $url): ?string
{
    $url = trim((string) $url);
    if ($url === '') {
        return null;
    }
    foreach ([
        '~youtube\.com/watch\?(?:.*&)?v=([A-Za-z0-9_-]{11})~i',
        '~youtu\.be/([A-Za-z0-9_-]{11})~i',
        '~youtube(?:-nocookie)?\.com/embed/([A-Za-z0-9_-]{11})~i',
        '~youtube\.com/shorts/([A-Za-z0-9_-]{11})~i',
    ] as $re) {
        if (preg_match($re, $url, $m)) {
            return $m[1];
        }
    }
    return preg_match('/^[A-Za-z0-9_-]{11}$/', $url) ? $url : null;
}

/** Fiches publiées d'une catégorie, sous-produits imbriqués. Null si base absente. */
function alam_catalogue_items(string $slug): ?array
{
    $pdo = alam_db();
    if (!$pdo) {
        return null;
    }
    try {
        $stmt = $pdo->prepare(
            'SELECT p.id, p.title, p.description, p.video_url, p.parent_id
               FROM products p
               JOIN categories c ON c.id = p.category_id
              WHERE p.is_published = 1 AND c.slug = ?
              ORDER BY p.position, p.id');
        $stmt->execute([$slug]);
        $rows = $stmt->fetchAll();
        if (!$rows) {
            return [];
        }

        $ids = array_column($rows, 'id');
        $in  = implode(',', array_fill(0, count($ids), '?'));

        $related = static function (string $sql) use ($pdo, $ids): array {
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
        $images = $related("SELECT product_id, path, alt FROM product_images
                             WHERE product_id IN ($in) ORDER BY position, id");
        $docs   = $related("SELECT product_id, path, label FROM product_docs
                             WHERE product_id IN ($in) ORDER BY id");
        $specs  = $related("SELECT product_id, label, value FROM product_specs
                             WHERE product_id IN ($in) ORDER BY position, id");

        $byId = [];
        foreach ($rows as $r) {
            $id = (int) $r['id'];
            $byId[$id] = [
                'title'       => $r['title'],
                'description' => $r['description'],
                'video'       => alam_youtube_id($r['video_url']),
                'images'      => $images[$id] ?? [],
                'docs'        => $docs[$id] ?? [],
                'specs'       => $specs[$id] ?? [],
                'variants'    => [],
                'parent'      => $r['parent_id'] !== null ? (int) $r['parent_id'] : null,
            ];
        }

        // Un seul niveau : un sous-produit dont le parent est dépublié remonte
        // au niveau principal plutôt que de disparaître de la page.
        $attached = [];
        foreach ($byId as $id => $prod) {
            $parent = $prod['parent'];
            if ($parent !== null && isset($byId[$parent]) && $byId[$parent]['parent'] === null) {
                unset($prod['parent'], $prod['variants']);
                $byId[$parent]['variants'][] = $prod;
                $attached[$id] = true;
            }
        }
        $out = [];
        foreach ($byId as $id => $prod) {
            if (!isset($attached[$id])) {
                unset($prod['parent']);
                $out[] = $prod;
            }
        }
        return $out;
    } catch (Throwable $e) {
        error_log('[alamstores] catalogue indisponible : ' . $e->getMessage());
        return null;
    }
}

/** Une fiche. Même balisage que product_card() dans tools/build_pages.py. */
function alam_product_card(array $prod, bool $isVariant = false): string
{
    $photo = $prod['images'][0]['path'] ?? '';
    $video = $prod['video'] ?? null;

    $media = '';
    if ($photo !== '') {
        $play = $video
            ? '<button class="product__play" type="button" data-video="' . alam_e($video)
              . '" aria-label="Voir la vidéo : ' . alam_e($prod['title']) . '">'
              . alam_icon('play') . '</button>'
            : '';
        $media = '<div class="product__media"><img src="' . alam_e($photo) . '" alt="'
               . alam_e($prod['title']) . '" loading="lazy" decoding="async">' . $play . '</div>';
    }

    $specs = '';
    $rows  = array_filter($prod['specs'] ?? [],
                          static function ($sp) { return $sp['label'] !== '' || $sp['value'] !== ''; });
    if ($rows) {
        $specs = '<dl class="product__specs">';
        foreach ($rows as $sp) {
            $specs .= '<div><dt>' . alam_e($sp['label']) . '</dt><dd>'
                    . alam_e($sp['value']) . '</dd></div>';
        }
        $specs .= '</dl>';
    }

    $actions = [];
    foreach ($prod['docs'] ?? [] as $doc) {
        $actions[] = '<a class="btn btn--ghost btn--sm" href="' . alam_e($doc['path']) . '" download>'
                   . alam_icon('download') . '<span>'
                   . alam_e($doc['label'] !== '' ? $doc['label'] : 'Fiche technique (PDF)')
                   . '</span></a>';
    }
    if ($video && $photo === '') {
        $actions[] = '<button class="btn btn--ghost btn--sm" type="button" data-video="'
                   . alam_e($video) . '">' . alam_icon('play') . '<span>Voir la vidéo</span></button>';
    }
    if (!$isVariant) {
        $actions[] = '<a class="btn btn--primary btn--sm" href="' . ALAM_LINK_DEVIS . '">'
                   . alam_icon('sparkle') . '<span>Demander un devis</span></a>';
    }

    $variants = '';
    $subs = array_filter($prod['variants'] ?? [],
                         static function ($v) { return ($v['title'] ?? '') !== ''; });
    if ($subs) {
        $variants = '<div class="product__variants"><h4>Déclinaisons</h4><ul>';
        foreach ($subs as $v) {
            $variants .= '<li>' . alam_product_card($v, true) . '</li>';
        }
        $variants .= '</ul></div>';
    }

    return '<article class="product' . ($isVariant ? ' product--sub' : '') . '">' . $media
         . '<div class="product__body"><h3>' . alam_e($prod['title']) . '</h3>'
         . (($prod['description'] ?? '') !== ''
            ? '<p>' . alam_e($prod['description']) . '</p>' : '')
         . $specs . $variants
         . ($actions ? '<div class="product__actions">' . implode('', $actions) . '</div>' : '')
         . '</div></article>';
}

/** La section « Nos modèles » d'une page de catégorie. */
function alam_catalogue(string $slug): void
{
    $items = alam_catalogue_items($slug);

    if ($items === null) {
        // Base injoignable : les fiches figées à la génération prennent le relais.
        static $fallback = null;
        if ($fallback === null) {
            $file = __DIR__ . '/catalogue-static.php';
            $fallback = is_file($file) ? (require $file) : [];
        }
        $cards = $fallback[$slug] ?? '';
    } else {
        $cards = '';
        foreach ($items as $prod) {
            $cards .= '<li>' . alam_product_card($prod) . '</li>';
        }
    }

    if (trim($cards) === '') {
        return;                       // rien à montrer : pas de section vide
    }
    ?>
  <section class="section reveal" id="produits">
    <div class="shell">
      <div class="section-head">
        <span class="eyebrow">Catalogue</span>
        <h2>Nos modèles</h2>
      </div>
      <ul class="product-grid">
        <?= $cards ?>
      </ul>
    </div>
  </section>
<?php
}
