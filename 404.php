<?php
/**
 * Page introuvable
 * Généré par tools/build_pages.py — les modifications faites ici seront perdues.
 */
$page = [
    'slug'        => '404',
    'title'       => 'Page introuvable | Alam Stores',
    'description' => 'La page demandée est introuvable.',
    'canonical'   => 'https://alamstores.ma/404',
    'og_image'    => 'https://alamstores.ma/assets/images/2019/05/Logo-stores-rideaux-maroc.png',
    'jsonld'      => '',
];
require __DIR__ . '/includes/header.php';
?>
  <section class="page-hero">
    <div class="shell page-hero__inner">
      <ol class="crumbs"><li><a href="index.php">Accueil</a></li>
        <li class="sep" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg></li><li aria-current="page">Erreur 404</li></ol>
      <h1>Page introuvable</h1>
      <p>La page que vous cherchez n'existe pas ou a été déplacée.</p>
    </div>
  </section>
  <section class="section">
    <div class="shell">
      <div class="btn-row">
        <a class="btn btn--primary btn--lg" href="index.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg><span>Retour à l'accueil</span></a>
        <a class="btn btn--ghost btn--lg" href="devis.php">Demander un devis</a>
      </div>
    </div>
  </section>
<?php require __DIR__ . '/includes/footer.php';
