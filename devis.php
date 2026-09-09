<?php
/**
 * Devis
 * Généré par tools/build_pages.py — les modifications faites ici seront perdues.
 */
$page = [
    'slug'        => 'devis',
    'title'       => 'Devis | Alam Stores',
    'description' => 'Demandez votre devis gratuit Alam Stores : stores intérieurs et extérieurs, pergolas, moustiquaires et motorisations sur mesure au Maroc.',
    'canonical'   => 'https://alamstores.ma/devis',
    'og_image'    => 'https://alamstores.ma/assets/images/2019/05/Logo-stores-rideaux-maroc.png',
    'jsonld'      => '<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Accueil","item":"https://alamstores.ma/"},{"@type":"ListItem","position":2,"name":"Devis","item":"https://alamstores.ma/devis"}]}</script>
',
];
require __DIR__ . '/includes/header.php';
?>
  <section class="page-hero">
    <div class="shell page-hero__inner">
      <ol class="crumbs"><li><a href="index.php">Accueil</a></li><li class="sep" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg></li><li aria-current="page">Devis</li></ol>
      <h1>Devis</h1>
      <p>Décrivez votre projet et nous revenons vers vous avec une proposition adaptée. L&#x27;étude et le devis sont gratuits et sans engagement.</p>
    </div>
  </section>
  <section class="section section--tight">
    <div class="shell">
      <div class="showroom">
        <div class="showroom__item">
          <span class="showroom__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
          <div>
            <strong>Showroom</strong>
            <p>Hay Nahda 1 Grp. AlAhd N° 1042 Rabat, Maroc</p>
          </div>
        </div>
        <div class="showroom__item">
          <span class="showroom__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
          <div>
            <strong>Téléphone</strong>
            <p><a href="tel:+212537759772">05 37 75 97 72</a><br>
               <a href="https://wa.me/212600055562" target="_blank" rel="noopener">WhatsApp&nbsp;: 06 00 05 55 62</a></p>
          </div>
        </div>
        <div class="showroom__item">
          <span class="showroom__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
          <div>
            <strong>Horaires d'ouverture</strong>
            <p>Lun - Ven : 8:30 - 12:30 et 14:30 - 18:30<br>Sam : 8:30 - 13:00</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php require __DIR__ . '/includes/quote-form.php'; ?>
  <section class="section">
    <div class="shell prose-wrap is-center">
      <div class="prose" data-content-slot="devis">
        <h2>Ce qui nous aide à répondre précisément</h2>
        <p>Plus votre demande est détaillée, plus notre première réponse sera juste. Si vous ne disposez pas de toutes ces informations, envoyez-nous simplement ce que vous avez : nous compléterons ensemble.</p>
        <ul>
          <li>Le type de produit envisagé, ou simplement le besoin à résoudre</li>
          <li>Le nombre d&#x27;ouvertures à équiper et leurs dimensions approximatives</li>
          <li>La pièce concernée et son orientation</li>
          <li>Une préférence entre commande manuelle et motorisée</li>
          <li>Des photos des ouvertures, très utiles pour un premier avis</li>
        </ul>
        <h2>La suite</h2>
        <p>Nous vous recontactons pour préciser le besoin, puis nous convenons d&#x27;une visite technique afin de relever les mesures exactes. Le devis définitif est établi à l&#x27;issue de cette visite, sur la base de dimensions réelles et non d&#x27;estimations.</p>
      </div>
    </div>
  </section>
<?php require __DIR__ . '/includes/footer.php';
