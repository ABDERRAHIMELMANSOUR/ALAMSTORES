<?php
declare(strict_types=1);

/**
 * Page générique d'une catégorie créée depuis le back-office.
 *
 *   /categorie.php?c=ma-nouvelle-categorie
 *
 * Les catégories d'origine ont chacune leur page (pergolas.php, parasols.php…),
 * avec leur texte et leurs photos. Celles ajoutées ensuite dans le tableau de
 * bord n'en ont pas : cette page les sert, avec leur fil d'Ariane, leurs
 * sous-catégories et leurs produits.
 */

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/nav.php';

$slug = isset($_GET['c']) ? (string) $_GET['c'] : '';
if (!preg_match('/^[a-z0-9-]{1,120}$/', $slug)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$categories = alam_categories();
$category   = null;
if ($categories !== null) {
    foreach ($categories as $row) {
        if ($row['slug'] === $slug) {
            $category = $row;
            break;
        }
    }
}

if (!$category || !$category['is_visible'] || !alam_ancestors_visible($category, $categories)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Fil d'Ariane : la catégorie et ses parents, de haut en bas.
$trail  = [];
$cursor = $category;
for ($i = 0; $i < 6 && $cursor; $i++) {
    array_unshift($trail, $cursor);
    $cursor = ($cursor['parent_id'] !== null && isset($categories[$cursor['parent_id']]))
        ? $categories[$cursor['parent_id']] : null;
}

$children = alam_children($slug, $categories);

$page = [
    'slug'        => $slug,
    'title'       => $category['name'] . ' | Alam Stores',
    'description' => $category['name'] . ' — Alam Stores, Le Spécialiste de l\'Aménagement et de la Décoration.',
    'canonical'   => 'https://alamstores.ma/categorie.php?c=' . rawurlencode($slug),
    'og_image'    => 'https://alamstores.ma/assets/images/2019/05/Logo-stores-rideaux-maroc.png',
    'jsonld'      => '',
];
require __DIR__ . '/includes/header.php';
?>
  <section class="page-hero">
    <div class="shell page-hero__inner">
      <ol class="crumbs">
        <li><a href="index.php">Accueil</a></li>
<?php foreach ($trail as $i => $node): ?>
        <li class="sep" aria-hidden="true"><?= alam_icon('chevron-right') ?></li>
<?php if ($i === count($trail) - 1): ?>
        <li aria-current="page"><?= alam_e($node['name']) ?></li>
<?php else: ?>
        <li><a href="<?= alam_e(alam_category_url($node['slug'])) ?>"><?= alam_e($node['name']) ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
      </ol>
      <h1><?= alam_e($category['name']) ?></h1>
      <p>Découvrez notre gamme <?= alam_e(mb_strtolower($category['name'], 'UTF-8')) ?>.
         Nos équipes prennent les mesures sur place et vous remettent un devis
         gratuit et sans engagement.</p>
    </div>
  </section>

<?php if ($children): ?>
  <section class="section section--alt reveal">
    <div class="shell">
      <div class="section-head">
        <span class="eyebrow">Notre gamme</span>
        <h2><?= alam_e($category['name']) ?></h2>
      </div>
      <ul class="card-grid">
<?php foreach ($children as $child): ?>
        <li><article class="card">
          <div class="card__body">
            <h3 class="card__title"><?= alam_e($child['label']) ?></h3>
            <div class="card__foot">
              <span class="card__more">Découvrir <?= alam_icon('arrow-right') ?></span>
            </div>
          </div>
          <a class="card__link" href="<?= alam_e($child['href']) ?>">
            <span class="sr-only"><?= alam_e($child['label']) ?></span></a>
        </article></li>
<?php endforeach; ?>
      </ul>
    </div>
  </section>
<?php endif; ?>

  <?php alam_catalogue($slug); ?>

  <section class="cta-band reveal">
    <div class="shell cta-band__inner">
      <div>
        <h2>Un projet de stores ou de pergola&nbsp;?</h2>
        <p>Décrivez votre besoin : nous étudions l'exposition, relevons les mesures
           sur place et vous remettons un devis gratuit et sans engagement.</p>
      </div>
      <a class="btn btn--primary btn--lg" href="devis.php">
        <?= alam_icon('sparkle') ?><span>Demander un devis</span></a>
    </div>
  </section>
<?php require __DIR__ . '/includes/footer.php';
