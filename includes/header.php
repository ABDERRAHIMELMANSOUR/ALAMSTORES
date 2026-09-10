<?php
/**
 * En-tête commun à toutes les pages. Généré par tools/build_pages.py.
 * $page est défini par la page appelante.
 */
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/catalogue.php';
require_once __DIR__ . '/nav.php';

$page = (array) ($page ?? []);
$slug = (string) ($page['slug'] ?? '');

// Une catégorie masquée depuis le back-office ne doit plus répondre, sinon
// elle resterait accessible par son adresse et par les moteurs de recherche.
if ($slug !== '' && !alam_category_visible($slug)) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

// Lu ici, avant la première ligne de HTML : une session ne peut plus s'ouvrir
// une fois les en-têtes partis. Le formulaire de devis s'en sert plus bas pour
// réafficher la saisie et les erreurs d'un envoi refusé.
alam_flash();
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= alam_e($page['title'] ?? 'Alam Stores') ?></title>
<meta name="description" content="<?= alam_e($page['description'] ?? '') ?>">
<link rel="canonical" href="<?= alam_e($page['canonical'] ?? '') ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Alam Stores">
<meta property="og:locale" content="fr_FR">
<meta property="og:title" content="<?= alam_e($page['title'] ?? '') ?>">
<meta property="og:description" content="<?= alam_e($page['description'] ?? '') ?>">
<meta property="og:url" content="<?= alam_e($page['canonical'] ?? '') ?>">
<meta property="og:image" content="<?= alam_e($page['og_image'] ?? '') ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="theme-color" content="#7d0e7c">
<link rel="icon" href="assets/images/2019/05/Logo-stores-rideaux-maroc.png" type="image/png">
<link rel="apple-touch-icon" href="assets/images/2019/05/Logo-stores-rideaux-maroc.png">
<link rel="preload" as="style" href="assets/css/main.min.css">
<link rel="stylesheet" href="assets/css/main.min.css">
<?= $page['jsonld'] ?? '' ?></head>
<body class="page-<?= alam_e($slug) ?>" data-slug="<?= alam_e($slug) ?>">
<a id="top"></a>
  <a class="skip-link" href="#main">Aller au contenu</a>
  <div class="topbar">
    <div class="shell topbar__inner">
      <ul class="topbar__list">
        <li class="is-optional"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg><span>Hay Nahda 1, Rabat</span></li>
        <li><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg><a href="tel:+212537759772">05 37 75 97 72</a></li>
        <li class="is-wide"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><span>Lun - Ven: 8:30 - 12:30 et 14:30 - 18:30 | Sam: 8:30 - 13:00</span></li>
      </ul>
    </div>
  </div>

  <header class="site-header" id="site-header">
    <div class="shell site-header__inner">
      <a class="brand" href="index.php" aria-label="Alam Stores, accueil">
        <img src="assets/images/2019/05/Logo-stores-rideaux-maroc.png" alt="Alam Stores &ndash; Le Spécialiste de l&#x27;Aménagement et de la Décoration" width="594" height="260">
      </a>

      <nav class="nav" aria-label="Navigation principale">
        <ul class="nav__list">
            <?= alam_nav_html() ?>
        </ul>
      </nav>

      <a class="btn btn--primary btn--sm header__cta" href="devis.php">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/></svg><span>Devis gratuit</span>
      </a>

      <button class="nav-toggle" type="button" id="nav-toggle"
              aria-expanded="false" aria-controls="drawer" aria-label="Ouvrir le menu">
        <span class="nav-toggle__bars"><span></span><span></span><span></span></span>
      </button>
    </div>
  </header>

  <div class="drawer-backdrop" id="drawer-backdrop" hidden></div>
  <aside class="drawer" id="drawer" aria-label="Menu" aria-hidden="true">
    <div class="drawer__head">
      <img src="assets/images/2019/05/Logo-stores-rideaux-maroc.png" alt="Alam Stores" width="594" height="260">
      <button class="drawer__close" type="button" id="drawer-close" aria-label="Fermer le menu"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="drawer__body">
      <ul class="drawer__list">
            <?= alam_drawer_html() ?>
      </ul>
    </div>
    <div class="drawer__foot">
      <div class="drawer__contact">
        <a href="tel:+212537759772">05 37 75 97 72</a>
        <a href="mailto:contact@alamstores.ma">contact@alamstores.ma</a>
      </div>
      <a class="btn btn--primary btn--block" href="devis.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/></svg><span>Devis gratuit</span></a>
    </div>
  </aside>
<main id="main">
