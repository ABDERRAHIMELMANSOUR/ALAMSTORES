<?php
declare(strict_types=1);

/**
 * Back-office Alam Stores — produits, sous-produits, médias, fiches
 * techniques et demandes de devis, branchés sur la base de données.
 *
 * Tout ce qui est enregistré ici ressort sur le site public, dans la page de
 * la catégorie choisie (api/catalog.php + assets/js/main.js).
 */

require dirname(__DIR__) . '/api/bootstrap.php';

send_security_headers(true);
start_session();

$installed = config_installed() && cfg('db');
$user = $installed ? current_user() : null;
$csrf = csrf_token();

function h(?string $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Back-office — Alam Stores</title>
<link rel="stylesheet" href="../assets/css/main.min.css">
<style>
  body { background: var(--slate-50, #f6f7f9); }
  .adm { max-width: 1180px; margin: 0 auto; padding: 24px 18px 80px; }
  .adm__top {
    display: flex; flex-wrap: wrap; gap: 14px; align-items: center;
    justify-content: space-between; padding: 18px 0 22px;
  }
  .adm__brand { display: flex; align-items: center; gap: 12px; }
  .adm__brand img { width: 122px; height: auto; }
  .adm__brand span { font-size: .78rem; font-weight: 700; color: var(--text-mute); }
  .adm__who { font-size: .82rem; color: var(--text-mute); }
  .adm__tabs { display: flex; gap: 8px; border-bottom: 1px solid var(--line); margin-bottom: 24px; }
  .adm__tab {
    appearance: none; background: none; border: 0; border-bottom: 2px solid transparent;
    padding: 11px 16px; font: inherit; font-weight: 700; font-size: .9rem;
    color: var(--text-mute); cursor: pointer;
  }
  .adm__tab[aria-selected="true"] { color: var(--brand-700); border-bottom-color: var(--brand-600); }
  .adm__badge {
    display: inline-block; min-width: 20px; padding: 1px 7px; margin-left: 7px;
    border-radius: 999px; background: var(--brand-600); color: #fff;
    font-size: .7rem; font-weight: 800;
  }
  .adm__panel { display: grid; gap: 22px; }
  @media (min-width: 940px) {
    #panel-products,
    #panel-categories { grid-template-columns: minmax(0, 1fr) 420px; align-items: start; }
  }
  .cat-tree { list-style: none; margin: 0; padding: 0; }
  .cat-tree ul { list-style: none; margin: 4px 0 4px 22px; padding: 0 0 0 12px;
                 border-left: 1px dashed var(--line); }
  .cat-row {
    display: flex; align-items: center; gap: 8px; padding: 7px 10px; margin-bottom: 4px;
    border: 1px solid var(--line); border-radius: var(--r-sm, 10px); background: #fff;
  }
  .cat-row.is-hidden { opacity: .58; border-style: dashed; }
  .cat-row.is-on { border-color: var(--brand-600); background: var(--brand-50); }
  .cat-row__name { font-weight: 700; font-size: .88rem; }
  .cat-row__meta { font-size: .74rem; color: var(--text-mute); }
  .cat-row__acts { margin-left: auto; display: flex; gap: 4px; flex: none; }
  .cat-row__acts button {
    appearance: none; border: 1px solid var(--line); background: #fff; border-radius: 7px;
    width: 28px; height: 28px; cursor: pointer; font-size: .8rem; line-height: 1;
    display: grid; place-items: center; padding: 0;
  }
  .cat-row__acts button:hover { border-color: var(--brand-400); color: var(--brand-700); }
  .cat-row__acts button.danger:hover { border-color: var(--danger, #b91c1c); color: var(--danger, #b91c1c); }
  .cat-fam { font-size: .74rem; font-weight: 800; letter-spacing: .06em;
             text-transform: uppercase; color: var(--text-mute); margin: 16px 0 6px; }
  .card-box {
    background: #fff; border: 1px solid var(--line); border-radius: var(--r-lg, 16px);
    padding: 20px; box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
  }
  .card-box h2 { font-size: 1.05rem; margin: 0 0 4px; }
  .card-box > p.hint { margin: 0 0 16px; font-size: .82rem; color: var(--text-mute); }
  .adm__list { list-style: none; margin: 0; padding: 0; display: grid; gap: 10px; }
  .adm__group { font-size: .74rem; font-weight: 800; letter-spacing: .06em;
                text-transform: uppercase; color: var(--text-mute); margin: 14px 0 2px; }
  .adm__item {
    display: flex; gap: 12px; align-items: center; width: 100%; text-align: left;
    padding: 10px 12px; border: 1px solid var(--line); border-radius: var(--r-md, 12px);
    background: #fff; cursor: pointer; font: inherit;
  }
  .adm__item:hover { border-color: var(--brand-300); background: var(--brand-50); }
  .adm__item.is-on { border-color: var(--brand-600); background: var(--brand-50); }
  .adm__item.is-sub { margin-left: 26px; border-style: dashed; }
  .adm__thumb { width: 52px; height: 40px; object-fit: cover; border-radius: 8px;
                background: var(--slate-100); flex: none; }
  .adm__item b { display: block; font-size: .9rem; }
  .adm__item small { color: var(--text-mute); font-size: .76rem; }
  .adm__flags { margin-left: auto; display: flex; gap: 5px; flex: none; }
  .pill { font-size: .68rem; font-weight: 800; padding: 3px 8px; border-radius: 999px;
          background: var(--slate-100); color: var(--slate-600); }
  .pill--on { background: #dcfce7; color: #166534; }
  .pill--off { background: #fee2e2; color: #991b1b; }
  .row { display: grid; gap: 14px; }
  @media (min-width: 560px) { .row--2 { grid-template-columns: 1fr 1fr; } }
  .adm label { display: block; font-size: .78rem; font-weight: 700; margin-bottom: 5px; }
  .adm input[type=text], .adm input[type=url], .adm input[type=number],
  .adm input[type=password], .adm select, .adm textarea {
    width: 100%; padding: 10px 12px; border: 1px solid var(--line);
    border-radius: var(--r-sm, 10px); font: inherit; background: #fff;
  }
  .adm textarea { min-height: 110px; resize: vertical; }
  .err { color: var(--danger, #b91c1c); font-size: .76rem; margin-top: 4px; min-height: 1em; }
  .req { color: var(--danger, #b91c1c); }
  .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(92px, 1fr)); gap: 10px; }
  .media { position: relative; border-radius: 10px; overflow: hidden; border: 1px solid var(--line); }
  .media img { width: 100%; aspect-ratio: 4 / 3; object-fit: cover; display: block; }
  .media button {
    position: absolute; top: 4px; right: 4px; width: 22px; height: 22px; border: 0;
    border-radius: 50%; background: rgba(15,23,42,.78); color: #fff; cursor: pointer;
    font-size: .8rem; line-height: 1;
  }
  .file-row { display: flex; align-items: center; gap: 10px; padding: 8px 10px;
              border: 1px solid var(--line); border-radius: 10px; font-size: .82rem; }
  .file-row button { margin-left: auto; }
  .spec-row { display: grid; grid-template-columns: 1fr 1fr auto; gap: 8px; align-items: center; }
  .mini { appearance: none; border: 1px solid var(--line); background: #fff; border-radius: 8px;
          padding: 6px 10px; font: inherit; font-size: .78rem; font-weight: 700; cursor: pointer; }
  .mini:hover { border-color: var(--brand-400); color: var(--brand-700); }
  .mini--danger:hover { border-color: var(--danger, #b91c1c); color: var(--danger, #b91c1c); }
  .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
  .table-wrap { overflow-x: auto; }
  table.leads { width: 100%; border-collapse: collapse; font-size: .82rem; }
  table.leads th, table.leads td { padding: 9px 10px; text-align: left; border-bottom: 1px solid var(--line); vertical-align: top; }
  table.leads th { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: var(--text-mute); white-space: nowrap; }
  table.leads tr.is-new td { background: var(--brand-50); }
  table.leads td.msg { max-width: 320px; white-space: pre-wrap; }
  .filters { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 16px; }
  .filters input, .filters select { max-width: 240px; }
  .note {
    margin-top: 20px; padding: 15px 17px; border-radius: var(--r-md, 12px);
    background: var(--brand-50); border: 1px solid var(--brand-100);
    font-size: .84rem; color: var(--brand-800); line-height: 1.6;
  }
  .note code { background: rgba(255,255,255,.8); padding: 1px 6px; border-radius: 5px;
               font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .9em; }
  .toast {
    position: fixed; left: 50%; bottom: 24px; transform: translate(-50%, 14px); z-index: 90;
    padding: 12px 22px; border-radius: 999px; background: var(--slate-900, #0f172a);
    color: #fff; font-size: .87rem; font-weight: 600; opacity: 0; visibility: hidden;
    transition: opacity .25s, transform .25s, visibility .25s; max-width: 90vw; text-align: center;
  }
  .toast.is-on { opacity: 1; visibility: visible; transform: translate(-50%, 0); }
  .toast.is-bad { background: var(--danger, #b91c1c); }
  .login { max-width: 380px; margin: 8vh auto; }
  .empty { padding: 26px; text-align: center; color: var(--text-mute); font-size: .86rem; }
</style>
</head>
<body>

<?php if (!$installed): ?>
<!-- ---------------------------------------------------- pas encore installé -->
<main class="adm">
  <div class="card-box login">
    <div class="adm__brand" style="margin-bottom:18px">
      <img src="../assets/images/2019/05/Logo-stores-rideaux-maroc.png" alt="Alam Stores">
    </div>
    <h2>Le back-office n'est pas encore installé</h2>
    <p class="hint">Le fichier <code>api/config.php</code> est absent, ou il ne
       contient pas les identifiants de votre base de données. Sans lui, ni le
       tableau de bord ni l'enregistrement des demandes de devis ne peuvent
       fonctionner.</p>
    <p style="margin-top:22px">
      <a class="btn btn--primary" href="setup.php" style="width:100%">Lancer l'installation</a></p>
    <p class="hint" style="margin-top:18px">L'installation demande le nom de votre
       base, l'utilisateur et le mot de passe MySQL — ils viennent du panneau de
       votre hébergeur, rubrique « Bases de données MySQL ». Le détail est dans
       <code>INSTALLATION.md</code>.</p>
    <p class="hint">Si <code>setup.php</code> a déjà été supprimé, envoyez
       <code>api/config.php</code> par FTP et rechargez cette page.</p>
  </div>
</main>

<?php elseif (!$user): ?>
<!-- ------------------------------------------------------------- connexion -->
<main class="adm">
  <div class="card-box login">
    <div class="adm__brand" style="margin-bottom:18px">
      <img src="../assets/images/2019/05/Logo-stores-rideaux-maroc.png" alt="Alam Stores">
    </div>
    <h2>Back-office</h2>
    <p class="hint">Réservé à l'équipe Alam Stores.</p>
    <form id="login-form" autocomplete="on">
      <div class="row">
        <div>
          <label for="lg-user">Identifiant</label>
          <input id="lg-user" name="username" type="text" autocomplete="username" required autofocus>
        </div>
        <div>
          <label for="lg-pass">Mot de passe</label>
          <input id="lg-pass" name="password" type="password" autocomplete="current-password" required>
        </div>
      </div>
      <p class="err" id="lg-err" role="alert"></p>
      <button class="btn btn--primary" type="submit" style="width:100%">Se connecter</button>
    </form>
  </div>
</main>
<script>
(function () {
  var form = document.getElementById('login-form');
  var err  = document.getElementById('lg-err');
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    err.textContent = '';
    fetch('../api/admin/session.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify({
        username: form.elements.username.value,
        password: form.elements.password.value
      })
    }).then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
      .then(function (res) {
        if (res.ok) { location.reload(); return; }
        err.textContent = res.d.error || 'Connexion refusée.';
      })
      .catch(function () { err.textContent = 'Serveur injoignable.'; });
  });
}());
</script>

<?php else: ?>
<!-- ------------------------------------------------------------ tableau de bord -->
<main class="adm">
  <div class="adm__top">
    <div class="adm__brand">
      <img src="../assets/images/2019/05/Logo-stores-rideaux-maroc.png" alt="Alam Stores">
      <span>Back-office</span>
    </div>
    <div class="adm__who">
      Connecté : <strong><?= h($user['username']) ?></strong>
      &nbsp;·&nbsp; <a href="../index.html">Voir le site</a>
      &nbsp;·&nbsp; <button class="mini" type="button" id="logout">Déconnexion</button>
    </div>
  </div>

  <div class="adm__tabs" role="tablist">
    <button class="adm__tab" id="tab-products" role="tab" aria-selected="true"
            aria-controls="panel-products" type="button">Produits</button>
    <button class="adm__tab" id="tab-categories" role="tab" aria-selected="false"
            aria-controls="panel-categories" type="button">Catégories</button>
    <button class="adm__tab" id="tab-leads" role="tab" aria-selected="false"
            aria-controls="panel-leads" type="button">Devis reçus<span class="adm__badge" id="leads-badge">0</span></button>
  </div>

  <!-- ------------------------------------------------------------- produits -->
  <section class="adm__panel" id="panel-products" role="tabpanel" aria-labelledby="tab-products">
    <div class="card-box">
      <h2>Catalogue</h2>
      <p class="hint">Rangé par catégorie, comme sur le site. Les sous-produits
         apparaissent en retrait sous leur produit principal.</p>
      <div class="actions" style="margin:0 0 16px">
        <button class="btn btn--primary btn--sm" type="button" id="new-product">Nouveau produit</button>
        <a class="mini" href="../api/admin/export.php">Exporter products.json</a>
      </div>
      <div id="product-list"><p class="empty">Chargement…</p></div>
    </div>

    <div class="card-box">
      <h2 id="form-title">Nouveau produit</h2>
      <p class="hint">Titre, description et au moins une photo sont obligatoires.
         La vidéo et la fiche technique sont facultatives.</p>
      <form id="product-form">
        <div class="row">
          <div>
            <label for="f-title">Titre <span class="req">*</span></label>
            <input id="f-title" name="title" type="text" maxlength="200" required>
            <p class="err" data-err="title"></p>
          </div>
          <div class="row row--2">
            <div>
              <label for="f-category">Catégorie <span class="req">*</span></label>
              <select id="f-category" name="category_id" required></select>
              <p class="err" data-err="category_id"></p>
            </div>
            <div>
              <label for="f-parent">Sous-produit de</label>
              <select id="f-parent" name="parent_id"></select>
              <p class="err" data-err="parent_id"></p>
            </div>
          </div>
          <div>
            <label for="f-desc">Description <span class="req">*</span></label>
            <textarea id="f-desc" name="description" maxlength="5000" required></textarea>
            <p class="err" data-err="description"></p>
          </div>

          <div>
            <label>Photos <span class="req">*</span></label>
            <div class="media-grid" id="f-images"></div>
            <p class="err" data-err="images"></p>
            <label class="mini" style="display:inline-block;margin-top:8px;cursor:pointer">
              Ajouter des photos
              <input type="file" id="up-photo" accept="image/jpeg,image/png,image/webp,image/gif"
                     multiple hidden>
            </label>
          </div>

          <div>
            <label for="f-video">Lien vidéo YouTube</label>
            <input id="f-video" name="video_url" type="url" maxlength="500"
                   placeholder="https://www.youtube.com/watch?v=…">
            <p class="err" data-err="video_url"></p>
            <p class="hint" style="margin:4px 0 0">Facultatif. La vidéo s'ouvre dans une fenêtre
               sur le site, elle n'est jamais chargée avant le clic.</p>
          </div>

          <div>
            <label>Fiches techniques (PDF)</label>
            <div class="row" id="f-docs" style="gap:8px"></div>
            <label class="mini" style="display:inline-block;margin-top:8px;cursor:pointer">
              Ajouter un PDF
              <input type="file" id="up-doc" accept="application/pdf" hidden>
            </label>
          </div>

          <div>
            <label>Caractéristiques</label>
            <div class="row" id="f-specs" style="gap:8px"></div>
            <button class="mini" type="button" id="add-spec" style="margin-top:8px">Ajouter une ligne</button>
          </div>

          <div class="row row--2">
            <div>
              <label for="f-position">Ordre d'affichage</label>
              <input id="f-position" name="position" type="number" min="0" max="9999" value="0">
            </div>
            <div>
              <label for="f-published">Visibilité</label>
              <select id="f-published" name="is_published">
                <option value="1">Publié sur le site</option>
                <option value="0">Brouillon (masqué)</option>
              </select>
            </div>
          </div>
        </div>

        <div class="actions">
          <button class="btn btn--primary btn--sm" type="submit">Enregistrer</button>
          <button class="mini" type="button" id="cancel-edit">Annuler</button>
          <button class="mini mini--danger" type="button" id="delete-product" hidden>Supprimer</button>
        </div>
      </form>
    </div>
  </section>

  <!-- ---------------------------------------------------------- catégories -->
  <section class="adm__panel" id="panel-categories" role="tabpanel"
           aria-labelledby="tab-categories" hidden>
    <div class="card-box">
      <h2>Arbre des catégories</h2>
      <p class="hint">Deux grandes familles, Intérieur et Extérieur, chacune avec
         ses sous-catégories. Masquer une catégorie la retire du menu, du pied de
         page et de la page d'accueil, et met sa page en 404 — sans rien
         supprimer.</p>
      <div class="actions" style="margin:0 0 16px">
        <button class="btn btn--primary btn--sm" type="button" id="new-category">Nouvelle catégorie</button>
      </div>
      <div id="category-tree"><p class="empty">Chargement…</p></div>
    </div>

    <div class="card-box">
      <h2 id="cat-form-title">Nouvelle catégorie</h2>
      <p class="hint">Une catégorie rangée sous une autre en hérite : elle reste
         du même côté du menu.</p>
      <form id="category-form">
        <div class="row">
          <div>
            <label for="c-name">Nom <span class="req">*</span></label>
            <input id="c-name" name="name" type="text" maxlength="180" required>
            <p class="err" data-cerr="name"></p>
          </div>
          <div>
            <label for="c-parent">Rangée sous</label>
            <select id="c-parent" name="parent_id"></select>
            <p class="err" data-cerr="parent_id"></p>
          </div>
          <div id="c-family-wrap">
            <label for="c-family">Famille</label>
            <select id="c-family" name="family">
              <option value="interieur">Stores Intérieurs</option>
              <option value="exterieur">Stores Extérieurs</option>
              <option value="autre">Autre</option>
            </select>
            <p class="hint" style="margin:4px 0 0">Utilisée seulement pour une
               catégorie de premier niveau.</p>
          </div>
          <div>
            <label for="c-visible">Visibilité</label>
            <select id="c-visible" name="is_visible">
              <option value="1">Affichée sur le site</option>
              <option value="0">Masquée</option>
            </select>
          </div>
        </div>
        <div class="actions">
          <button class="btn btn--primary btn--sm" type="submit">Enregistrer</button>
          <button class="mini" type="button" id="cat-cancel">Annuler</button>
          <button class="mini mini--danger" type="button" id="cat-delete" hidden>Supprimer</button>
        </div>
      </form>
      <div class="note">
        <strong>Ce que voit un visiteur</strong><br>
        Les catégories d'origine ont chacune leur page (<code>pergolas.php</code>,
        <code>parasols.php</code>…) avec leur texte et leurs photos. Une catégorie
        créée ici n'a pas de page à elle : elle est servie par
        <code>categorie.php</code>, avec son fil d'Ariane, ses sous-catégories et
        ses produits. Pour lui donner une vraie page rédigée, ajoutez son slug à
        <code>PAGES</code> dans <code>tools/build_pages.py</code> et relancez le
        générateur.
      </div>
    </div>
  </section>

  <!-- --------------------------------------------------------------- leads -->
  <section class="adm__panel" id="panel-leads" role="tabpanel" aria-labelledby="tab-leads" hidden>
    <div class="card-box">
      <h2>Demandes de devis</h2>
      <p class="hint">Enregistrées en base à chaque envoi du formulaire de
         <a href="../devis.html">devis.html</a>, depuis n'importe quel appareil.</p>

      <div class="filters">
        <input type="text" id="lead-search" placeholder="Rechercher (nom, société, e-mail, ville…)">
        <select id="lead-statut"><option value="">Tous les statuts</option></select>
        <select id="lead-category"><option value="">Toutes les catégories</option></select>
        <a class="mini" id="lead-export" href="../api/admin/leads.php?format=csv">Exporter en CSV</a>
      </div>

      <div class="table-wrap">
        <table class="leads">
          <thead><tr>
            <th>Date</th><th>Statut</th><th>Entreprise</th><th>Contact</th>
            <th>Catégorie</th><th>Téléphone</th><th>E-mail</th><th>Ville</th>
            <th>Message</th><th></th>
          </tr></thead>
          <tbody id="lead-rows"></tbody>
        </table>
      </div>
      <p class="empty" id="lead-empty" hidden>Aucune demande pour l'instant.</p>
      <div class="actions" id="lead-pager"></div>

      <div class="note" id="local-note" hidden>
        <strong>Demandes enregistrées sur cet appareil</strong><br>
        <span id="local-count"></span> demande(s) sont encore stockées dans le navigateur
        (l'ancien fonctionnement, avant la base de données).
        <button class="mini" type="button" id="local-export" style="margin-top:8px">Les exporter en CSV</button>
      </div>
    </div>
  </section>
</main>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script>
(function () {
  'use strict';

  var CSRF = <?= json_encode($csrf, JSON_UNESCAPED_SLASHES) ?>;
  var API  = '../api/';

  var state = { products: [], categories: [], cats: [], editing: null,
               editingCat: null, images: [], docs: [], specs: [] };

  var $  = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  function toast(msg, bad) {
    var el = $('#toast');
    el.textContent = msg;
    el.className = 'toast is-on' + (bad ? ' is-bad' : '');
    window.clearTimeout(toast._t);
    toast._t = window.setTimeout(function () { el.className = 'toast'; }, 3600);
  }

  /** Appel JSON authentifié. Rejette avec {error, fields} pour les 4xx. */
  function api(path, options) {
    options = options || {};
    var headers = { 'X-CSRF-Token': CSRF };
    if (options.body && !(options.body instanceof FormData)) {
      headers['Content-Type'] = 'application/json';
      options.body = JSON.stringify(options.body);
    }
    options.headers = headers;
    options.credentials = 'same-origin';
    return fetch(API + path, options).then(function (r) {
      if (r.status === 401) { location.reload(); throw new Error('session'); }
      return r.json().catch(function () { return {}; }).then(function (d) {
        if (d && d.not_setup) {
          // api/config.php a disparu depuis le chargement de la page.
          window.location.href = 'setup.php';
          throw new Error('setup');
        }
        if (!r.ok) { throw d && d.error ? d : new Error('Erreur ' + r.status); }
        return d;
      });
    });
  }

  function esc(v) {
    return String(v == null ? '' : v)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /* ----------------------------------------------------------- onglets --- */
  function showTab(name) {
    ['products', 'categories', 'leads'].forEach(function (t) {
      $('#tab-' + t).setAttribute('aria-selected', String(t === name));
      $('#panel-' + t).hidden = t !== name;
    });
    if (name === 'leads') { loadLeads(); }
    if (name === 'categories') { loadCategories(); }
  }
  $('#tab-products').addEventListener('click', function () { showTab('products'); });
  $('#tab-categories').addEventListener('click', function () { showTab('categories'); });
  $('#tab-leads').addEventListener('click', function () { showTab('leads'); });

  $('#logout').addEventListener('click', function () {
    api('admin/session.php', { method: 'DELETE' }).then(function () { location.reload(); });
  });

  /* ---------------------------------------------------------- catalogue --- */
  function loadProducts() {
    return api('admin/products.php').then(function (d) {
      state.products   = d.products || [];
      state.categories = d.categories || [];
      if (d.csrf) { CSRF = d.csrf; }
      fillCategorySelect();
      fillParentSelect();
      renderList();
    }).catch(function (e) { toast(e.error || 'Chargement impossible.', true); });
  }

  var FAMILY = { interieur: 'Stores Intérieurs', exterieur: 'Stores Extérieurs', autre: 'Autres' };

  function fillCategorySelect() {
    var sel = $('#f-category');
    var current = sel.value;
    sel.innerHTML = '<option value="">— Choisir —</option>';
    ['interieur', 'exterieur', 'autre'].forEach(function (fam) {
      var inFam = state.categories.filter(function (c) { return c.family === fam; });
      if (!inFam.length) { return; }
      var group = document.createElement('optgroup');
      group.label = FAMILY[fam];
      inFam.forEach(function (c) {
        var o = document.createElement('option');
        o.value = c.id;
        o.textContent = c.parent_name ? c.parent_name + ' › ' + c.name : c.name;
        group.appendChild(o);
      });
      sel.appendChild(group);
    });
    sel.value = current;
  }

  function fillParentSelect() {
    var sel = $('#f-parent');
    var current = sel.value;
    sel.innerHTML = '<option value="">— Produit principal —</option>';
    state.products.filter(function (p) {
      return p.parent_id === null && (!state.editing || p.id !== state.editing);
    }).forEach(function (p) {
      var o = document.createElement('option');
      o.value = p.id;
      o.textContent = p.title + ' (' + p.category_name + ')';
      sel.appendChild(o);
    });
    sel.value = current;
  }

  function renderList() {
    var host = $('#product-list');
    if (!state.products.length) {
      host.innerHTML = '<p class="empty">Aucun produit. Cliquez sur « Nouveau produit ».</p>';
      return;
    }
    var byCat = {};
    state.products.forEach(function (p) {
      (byCat[p.category_name] = byCat[p.category_name] || []).push(p);
    });

    var html = '';
    Object.keys(byCat).forEach(function (cat) {
      html += '<p class="adm__group">' + esc(cat) + '</p><ul class="adm__list">';
      var list = byCat[cat];
      var tops = list.filter(function (p) { return p.parent_id === null; });
      var subs = list.filter(function (p) { return p.parent_id !== null; });
      tops.forEach(function (p) {
        html += itemHtml(p, false);
        subs.filter(function (s) { return s.parent_id === p.id; })
            .forEach(function (s) { html += itemHtml(s, true); });
      });
      // Un sous-produit dont le parent est dans une autre catégorie.
      subs.filter(function (s) {
        return !tops.some(function (p) { return p.id === s.parent_id; });
      }).forEach(function (s) { html += itemHtml(s, true); });
      html += '</ul>';
    });
    host.innerHTML = html;

    $$('.adm__item', host).forEach(function (btn) {
      btn.addEventListener('click', function () { edit(Number(btn.dataset.id)); });
    });
  }

  function itemHtml(p, isSub) {
    var thumb = p.images && p.images[0]
      ? '<img class="adm__thumb" src="../' + esc(p.images[0].path) + '" alt="">'
      : '<span class="adm__thumb"></span>';
    var flags = '';
    if (p.video_url) { flags += '<span class="pill">vidéo</span>'; }
    if (p.docs && p.docs.length) { flags += '<span class="pill">PDF</span>'; }
    flags += p.is_published
      ? '<span class="pill pill--on">publié</span>'
      : '<span class="pill pill--off">brouillon</span>';
    return '<li><button class="adm__item' + (isSub ? ' is-sub' : '')
         + (state.editing === p.id ? ' is-on' : '') + '" type="button" data-id="' + p.id + '">'
         + thumb + '<span><b>' + esc(p.title) + '</b><small>'
         + (p.images ? p.images.length : 0) + ' photo(s)'
         + (isSub ? ' · sous-produit' : '') + '</small></span>'
         + '<span class="adm__flags">' + flags + '</span></button></li>';
  }

  /* -------------------------------------------------------- formulaire --- */
  function clearErrors() {
    $$('[data-err]').forEach(function (el) { el.textContent = ''; });
  }

  function reset() {
    state.editing = null;
    state.images = []; state.docs = []; state.specs = [];
    $('#product-form').reset();
    $('#f-published').value = '1';
    $('#form-title').textContent = 'Nouveau produit';
    $('#delete-product').hidden = true;
    clearErrors();
    fillParentSelect();
    renderImages(); renderDocs(); renderSpecs();
    renderList();
  }

  function edit(id) {
    var p = state.products.filter(function (x) { return x.id === id; })[0];
    if (!p) { return; }
    state.editing = id;
    state.images = (p.images || []).map(function (i) { return { path: i.path, alt: i.alt || '' }; });
    state.docs   = (p.docs || []).map(function (d) {
      return { path: d.path, label: d.label, size_bytes: Number(d.size_bytes || 0) };
    });
    state.specs  = (p.specs || []).map(function (s) { return { label: s.label, value: s.value }; });

    $('#f-title').value    = p.title;
    $('#f-desc').value     = p.description;
    $('#f-video').value    = p.video_url || '';
    $('#f-position').value = p.position;
    $('#f-published').value = p.is_published ? '1' : '0';
    fillParentSelect();
    $('#f-category').value = p.category_id;
    $('#f-parent').value   = p.parent_id === null ? '' : p.parent_id;

    $('#form-title').textContent = 'Modifier : ' + p.title;
    $('#delete-product').hidden = false;
    clearErrors();
    renderImages(); renderDocs(); renderSpecs(); renderList();
    $('#f-title').focus();
    $('#f-title').scrollIntoView({ block: 'center', behavior: 'smooth' });
  }

  function renderImages() {
    var host = $('#f-images');
    host.innerHTML = state.images.map(function (img, i) {
      return '<div class="media"><img src="../' + esc(img.path) + '" alt="">'
           + '<button type="button" data-i="' + i + '" aria-label="Retirer la photo">&times;</button></div>';
    }).join('') || '<p class="hint" style="grid-column:1/-1;margin:0">Aucune photo pour l’instant.</p>';
    $$('button', host).forEach(function (b) {
      b.addEventListener('click', function () {
        state.images.splice(Number(b.dataset.i), 1);
        renderImages();
      });
    });
  }

  function renderDocs() {
    var host = $('#f-docs');
    host.innerHTML = state.docs.map(function (d, i) {
      var kb = d.size_bytes ? ' · ' + Math.round(d.size_bytes / 1024) + ' Ko' : '';
      return '<div class="file-row"><span>' + esc(d.label) + kb + '</span>'
           + '<button class="mini mini--danger" type="button" data-i="' + i + '">Retirer</button></div>';
    }).join('');
    $$('button', host).forEach(function (b) {
      b.addEventListener('click', function () {
        state.docs.splice(Number(b.dataset.i), 1);
        renderDocs();
      });
    });
  }

  function renderSpecs() {
    var host = $('#f-specs');
    host.innerHTML = state.specs.map(function (s, i) {
      return '<div class="spec-row">'
           + '<input type="text" value="' + esc(s.label) + '" data-k="label" data-i="' + i + '" placeholder="Intitulé" maxlength="160">'
           + '<input type="text" value="' + esc(s.value) + '" data-k="value" data-i="' + i + '" placeholder="Valeur" maxlength="300">'
           + '<button class="mini mini--danger" type="button" data-i="' + i + '">&times;</button></div>';
    }).join('');
    $$('input', host).forEach(function (inp) {
      inp.addEventListener('input', function () {
        state.specs[Number(inp.dataset.i)][inp.dataset.k] = inp.value;
      });
    });
    $$('button', host).forEach(function (b) {
      b.addEventListener('click', function () {
        state.specs.splice(Number(b.dataset.i), 1);
        renderSpecs();
      });
    });
  }

  $('#add-spec').addEventListener('click', function () {
    state.specs.push({ label: '', value: '' });
    renderSpecs();
  });

  /* --------------------------------------------------------- téléversement */
  function upload(file, kind) {
    var fd = new FormData();
    fd.append('file', file);
    fd.append('kind', kind);
    return api('admin/upload.php', { method: 'POST', body: fd });
  }

  $('#up-photo').addEventListener('change', function (e) {
    var files = Array.prototype.slice.call(e.target.files || []);
    e.target.value = '';
    if (!files.length) { return; }
    toast('Envoi de ' + files.length + ' photo(s)…');
    files.reduce(function (chain, file) {
      return chain.then(function () {
        return upload(file, 'photo').then(function (r) {
          state.images.push({ path: r.path, alt: '' });
          renderImages();
        });
      });
    }, Promise.resolve())
      .then(function () { toast('Photos ajoutées.'); })
      .catch(function (err) { toast(err.error || 'Envoi impossible.', true); });
  });

  $('#up-doc').addEventListener('change', function (e) {
    var file = (e.target.files || [])[0];
    e.target.value = '';
    if (!file) { return; }
    upload(file, 'doc').then(function (r) {
      state.docs.push({
        path: r.path,
        label: r.label || 'Fiche technique (PDF)',
        size_bytes: r.size_bytes
      });
      renderDocs();
      toast('Fiche technique ajoutée.');
    }).catch(function (err) { toast(err.error || 'Envoi impossible.', true); });
  });

  /* ----------------------------------------------------- enregistrement --- */
  $('#product-form').addEventListener('submit', function (e) {
    e.preventDefault();
    clearErrors();

    var payload = {
      title:        $('#f-title').value,
      description:  $('#f-desc').value,
      category_id:  Number($('#f-category').value || 0),
      parent_id:    $('#f-parent').value === '' ? null : Number($('#f-parent').value),
      video_url:    $('#f-video').value,
      is_published: $('#f-published').value === '1',
      position:     Number($('#f-position').value || 0),
      images:       state.images,
      docs:         state.docs,
      specs:        state.specs.filter(function (s) { return s.label || s.value; })
    };

    var editing = state.editing;
    api('admin/products.php' + (editing ? '?id=' + editing : ''),
        { method: editing ? 'PUT' : 'POST', body: payload })
      .then(function () {
        toast(editing ? 'Produit mis à jour.' : 'Produit ajouté.');
        return loadProducts();
      })
      .then(reset)
      .catch(function (err) {
        if (err && err.fields) {
          Object.keys(err.fields).forEach(function (k) {
            var slot = $('[data-err="' + k + '"]');
            if (slot) { slot.textContent = err.fields[k]; }
          });
        }
        toast((err && err.error) || 'Enregistrement impossible.', true);
      });
  });

  $('#new-product').addEventListener('click', reset);
  $('#cancel-edit').addEventListener('click', reset);

  $('#delete-product').addEventListener('click', function () {
    if (!state.editing) { return; }
    if (!window.confirm('Supprimer ce produit ainsi que ses sous-produits ? '
                      + 'Cette action est définitive.')) { return; }
    api('admin/products.php?id=' + state.editing, { method: 'DELETE' })
      .then(function () { toast('Produit supprimé.'); return loadProducts(); })
      .then(reset)
      .catch(function (err) { toast((err && err.error) || 'Suppression impossible.', true); });
  });

  /* --------------------------------------------------------- catégories --- */
  var FAMILY_ORDER = ['interieur', 'exterieur', 'autre'];

  function loadCategories() {
    return api('admin/categories.php').then(function (d) {
      state.cats = d.categories || [];
      if (d.csrf) { CSRF = d.csrf; }
      fillParentOptions();
      renderTree();
      // La liste des catégories du formulaire produit suit la même source.
      return loadProducts();
    }).catch(function (e) { toast((e && e.error) || 'Chargement impossible.', true); });
  }

  function catChildren(parentId) {
    return (state.cats || []).filter(function (c) { return c.parent_id === parentId; });
  }

  function catById(id) {
    return (state.cats || []).filter(function (c) { return c.id === id; })[0] || null;
  }

  function renderTree() {
    var host = $('#category-tree');
    if (!state.cats || !state.cats.length) {
      host.innerHTML = '<p class="empty">Aucune catégorie. La base n’a peut-être pas '
                     + 'été initialisée (db/seed_categories.sql).</p>';
      return;
    }
    var html = '';
    FAMILY_ORDER.forEach(function (fam) {
      var roots = catChildren(null).filter(function (c) { return c.family === fam; });
      if (!roots.length) { return; }
      html += '<p class="cat-fam">' + esc(FAMILY[fam]) + '</p><ul class="cat-tree">'
            + roots.map(branch).join('') + '</ul>';
    });
    host.innerHTML = html;

    $$('[data-cat-edit]', host).forEach(function (b) {
      b.addEventListener('click', function () { editCategory(Number(b.dataset.catEdit)); });
    });
    $$('[data-cat-toggle]', host).forEach(function (b) {
      b.addEventListener('click', function () { toggleCategory(Number(b.dataset.catToggle)); });
    });
    $$('[data-cat-move]', host).forEach(function (b) {
      b.addEventListener('click', function () {
        moveCategory(Number(b.dataset.catMove), Number(b.dataset.dir));
      });
    });
    $$('[data-cat-del]', host).forEach(function (b) {
      b.addEventListener('click', function () { deleteCategory(Number(b.dataset.catDel)); });
    });
    $$('[data-cat-add]', host).forEach(function (b) {
      b.addEventListener('click', function () { newCategory(Number(b.dataset.catAdd)); });
    });
  }

  function branch(cat) {
    var kids = catChildren(cat.id);
    var siblings = catChildren(cat.parent_id);
    var i = siblings.indexOf(cat);
    var meta = [];
    if (cat.product_count) { meta.push(cat.product_count + ' produit(s)'); }
    if (kids.length) { meta.push(kids.length + ' sous-catégorie(s)'); }
    meta.push(cat.has_page ? 'page dédiée' : 'page générique');
    if (!cat.is_visible) { meta.push('masquée'); }

    return '<li><div class="cat-row' + (cat.is_visible ? '' : ' is-hidden')
      + (state.editingCat === cat.id ? ' is-on' : '') + '">'
      + '<span><span class="cat-row__name">' + esc(cat.name) + '</span><br>'
      + '<span class="cat-row__meta">' + esc(meta.join(' · ')) + '</span></span>'
      + '<span class="cat-row__acts">'
      + '<button type="button" data-cat-move="' + cat.id + '" data-dir="-1"'
      + (i <= 0 ? ' disabled' : '') + ' title="Monter">&#9650;</button>'
      + '<button type="button" data-cat-move="' + cat.id + '" data-dir="1"'
      + (i >= siblings.length - 1 ? ' disabled' : '') + ' title="Descendre">&#9660;</button>'
      + '<button type="button" data-cat-toggle="' + cat.id + '" title="'
      + (cat.is_visible ? 'Masquer' : 'Afficher') + '">' + (cat.is_visible ? '&#128065;' : '&#128584;') + '</button>'
      + '<button type="button" data-cat-add="' + cat.id + '" title="Ajouter une sous-catégorie">+</button>'
      + '<button type="button" data-cat-edit="' + cat.id + '" title="Renommer">&#9998;</button>'
      + '<button type="button" class="danger" data-cat-del="' + cat.id + '" title="Supprimer">&times;</button>'
      + '</span></div>'
      + (kids.length ? '<ul>' + kids.map(branch).join('') + '</ul>' : '')
      + '</li>';
  }

  function fillParentOptions(exclude) {
    var sel = $('#c-parent');
    var current = sel.value;
    sel.innerHTML = '<option value="">— Catégorie principale —</option>';
    FAMILY_ORDER.forEach(function (fam) {
      var inFam = (state.cats || []).filter(function (c) { return c.family === fam; });
      if (!inFam.length) { return; }
      var group = document.createElement('optgroup');
      group.label = FAMILY[fam];
      inFam.forEach(function (c) {
        // Une catégorie ne peut pas être rangée sous elle-même ni sous l'une
        // de ses descendantes : la branche se détacherait de l'arbre.
        if (exclude && (c.id === exclude || isDescendant(c.id, exclude))) { return; }
        var o = document.createElement('option');
        o.value = c.id;
        var parent = c.parent_id ? catById(c.parent_id) : null;
        o.textContent = parent ? parent.name + ' › ' + c.name : c.name;
        group.appendChild(o);
      });
      if (group.children.length) { sel.appendChild(group); }
    });
    sel.value = current;
  }

  function isDescendant(id, ancestor) {
    var cursor = catById(id);
    for (var i = 0; i < 10 && cursor; i++) {
      if (cursor.id === ancestor) { return true; }
      cursor = cursor.parent_id ? catById(cursor.parent_id) : null;
    }
    return false;
  }

  function syncFamilyField() {
    $('#c-family-wrap').hidden = $('#c-parent').value !== '';
  }
  $('#c-parent').addEventListener('change', syncFamilyField);

  function resetCategoryForm() {
    state.editingCat = null;
    $('#category-form').reset();
    $('#cat-form-title').textContent = 'Nouvelle catégorie';
    $('#cat-delete').hidden = true;
    $$('[data-cerr]').forEach(function (el) { el.textContent = ''; });
    fillParentOptions();
    $('#c-parent').value = '';
    syncFamilyField();
    renderTree();
  }

  function newCategory(parentId) {
    resetCategoryForm();
    if (parentId) {
      $('#c-parent').value = String(parentId);
      syncFamilyField();
      var parent = catById(parentId);
      $('#cat-form-title').textContent = parent
        ? 'Nouvelle sous-catégorie de « ' + parent.name + ' »' : 'Nouvelle catégorie';
    }
    $('#c-name').focus();
  }

  function editCategory(id) {
    var cat = catById(id);
    if (!cat) { return; }
    state.editingCat = id;
    fillParentOptions(id);
    $('#c-name').value = cat.name;
    $('#c-parent').value = cat.parent_id === null ? '' : String(cat.parent_id);
    $('#c-family').value = cat.family;
    $('#c-visible').value = cat.is_visible ? '1' : '0';
    syncFamilyField();
    $('#cat-form-title').textContent = 'Modifier : ' + cat.name;
    $('#cat-delete').hidden = false;
    $$('[data-cerr]').forEach(function (el) { el.textContent = ''; });
    renderTree();
    $('#c-name').focus();
  }

  function toggleCategory(id) {
    var cat = catById(id);
    if (!cat) { return; }
    api('admin/categories.php?id=' + id,
        { method: 'PUT', body: { is_visible: !cat.is_visible } })
      .then(function () {
        toast(cat.is_visible ? 'Catégorie masquée sur le site.' : 'Catégorie affichée sur le site.');
        return loadCategories();
      })
      .catch(function (e) { toast((e && e.error) || 'Modification impossible.', true); });
  }

  /* Réordonner, c'est échanger la position avec le voisin du même niveau. */
  function moveCategory(id, dir) {
    var cat = catById(id);
    if (!cat) { return; }
    var siblings = catChildren(cat.parent_id);
    var i = siblings.indexOf(cat);
    var j = i + dir;
    if (j < 0 || j >= siblings.length) { return; }
    var other = siblings[j];
    api('admin/categories.php?id=' + cat.id, { method: 'PUT', body: { position: other.position } })
      .then(function () {
        return api('admin/categories.php?id=' + other.id,
                   { method: 'PUT', body: { position: cat.position } });
      })
      .then(loadCategories)
      .catch(function (e) { toast((e && e.error) || 'Déplacement impossible.', true); });
  }

  function deleteCategory(id) {
    var cat = catById(id);
    if (!cat) { return; }
    if (!window.confirm('Supprimer la catégorie « ' + cat.name + ' » ?')) { return; }

    api('admin/categories.php?id=' + id, { method: 'DELETE' })
      .then(function () { toast('Catégorie supprimée.'); return loadCategories(); })
      .then(resetCategoryForm)
      .catch(function (err) {
        if (err && err.needs_force) {
          var what = [];
          if (err.products) { what.push(err.products + ' produit(s)'); }
          if (err.children) { what.push(err.children + ' sous-catégorie(s)'); }
          if (!window.confirm('« ' + cat.name + ' » contient ' + what.join(' et ')
              + '.\nTout supprimer définitivement, y compris les produits ?')) { return; }
          return api('admin/categories.php?id=' + id + '&force=1', { method: 'DELETE' })
            .then(function () { toast('Catégorie et contenu supprimés.'); return loadCategories(); })
            .then(resetCategoryForm);
        }
        toast((err && err.error) || 'Suppression impossible.', true);
      });
  }

  $('#category-form').addEventListener('submit', function (e) {
    e.preventDefault();
    $$('[data-cerr]').forEach(function (el) { el.textContent = ''; });

    var payload = {
      name:       $('#c-name').value,
      parent_id:  $('#c-parent').value === '' ? null : Number($('#c-parent').value),
      family:     $('#c-family').value,
      is_visible: $('#c-visible').value === '1'
    };
    var editing = state.editingCat;

    api('admin/categories.php' + (editing ? '?id=' + editing : ''),
        { method: editing ? 'PUT' : 'POST', body: payload })
      .then(function () {
        toast(editing ? 'Catégorie mise à jour.' : 'Catégorie ajoutée.');
        return loadCategories();
      })
      .then(resetCategoryForm)
      .catch(function (err) {
        if (err && err.fields) {
          Object.keys(err.fields).forEach(function (k) {
            var slot = $('[data-cerr="' + k + '"]');
            if (slot) { slot.textContent = err.fields[k]; }
          });
        }
        toast((err && err.error) || 'Enregistrement impossible.', true);
      });
  });

  $('#new-category').addEventListener('click', function () { newCategory(null); });
  $('#cat-cancel').addEventListener('click', resetCategoryForm);
  $('#cat-delete').addEventListener('click', function () {
    if (state.editingCat) { deleteCategory(state.editingCat); }
  });

  /* -------------------------------------------------------------- leads --- */
  var leadTimer = null;

  function leadQuery() {
    var params = new URLSearchParams();
    if ($('#lead-search').value.trim()) { params.set('search', $('#lead-search').value.trim()); }
    if ($('#lead-statut').value) { params.set('statut', $('#lead-statut').value); }
    if ($('#lead-category').value) { params.set('category', $('#lead-category').value); }
    return params;
  }

  function loadLeads(page) {
    var params = leadQuery();
    if (page) { params.set('page', String(page)); }
    $('#lead-export').href = API + 'admin/leads.php?format=csv&' + params.toString();

    return api('admin/leads.php?' + params.toString()).then(function (d) {
      $('#leads-badge').textContent = String(d.unread || 0);
      fillFilter('#lead-statut', d.statuts, 'Tous les statuts');
      fillFilter('#lead-category', d.categories, 'Toutes les catégories');
      renderLeads(d);
    }).catch(function (err) { toast((err && err.error) || 'Chargement impossible.', true); });
  }

  function fillFilter(sel, values, label) {
    var el = $(sel);
    var current = el.value;
    el.innerHTML = '<option value="">' + label + '</option>'
      + (values || []).map(function (v) {
          return '<option value="' + esc(v) + '">' + esc(v) + '</option>';
        }).join('');
    el.value = current;
  }

  function renderLeads(d) {
    var rows = d.leads || [];
    $('#lead-empty').hidden = rows.length > 0;
    $('#lead-rows').innerHTML = rows.map(function (l) {
      return '<tr' + (l.is_read ? '' : ' class="is-new"') + '>'
        + '<td>' + esc(formatDate(l.created_at)) + '</td>'
        + '<td>' + esc(l.statut) + '</td>'
        + '<td>' + esc(l.company) + '</td>'
        + '<td>' + esc((l.firstname + ' ' + l.lastname).trim()) + '</td>'
        + '<td>' + esc(l.category) + '</td>'
        + '<td><a href="tel:' + esc(l.phone) + '">' + esc(l.phone) + '</a></td>'
        + '<td><a href="mailto:' + esc(l.email) + '">' + esc(l.email) + '</a></td>'
        + '<td>' + esc(l.city) + '</td>'
        + '<td class="msg">' + esc(l.message) + '</td>'
        + '<td style="white-space:nowrap">'
        + '<button class="mini" type="button" data-read="' + l.id + '">'
        + (l.is_read ? 'Non lu' : 'Lu') + '</button> '
        + '<button class="mini mini--danger" type="button" data-del="' + l.id + '">&times;</button>'
        + '</td></tr>';
    }).join('');

    $$('#lead-rows [data-read]').forEach(function (b) {
      b.addEventListener('click', function () {
        var lead = rows.filter(function (l) { return String(l.id) === b.dataset.read; })[0];
        api('admin/leads.php?id=' + b.dataset.read,
            { method: 'PATCH', body: { is_read: !(lead && lead.is_read) } })
          .then(function () { loadLeads(d.page); });
      });
    });
    $$('#lead-rows [data-del]').forEach(function (b) {
      b.addEventListener('click', function () {
        if (!window.confirm('Supprimer cette demande ?')) { return; }
        api('admin/leads.php?id=' + b.dataset.del, { method: 'DELETE' })
          .then(function () { toast('Demande supprimée.'); loadLeads(d.page); });
      });
    });

    var pager = $('#lead-pager');
    pager.innerHTML = '';
    if ((d.pages || 1) > 1) {
      for (var i = 1; i <= d.pages; i++) {
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'mini';
        b.textContent = String(i);
        if (i === d.page) { b.style.borderColor = 'var(--brand-600)'; }
        b.addEventListener('click', (function (n) {
          return function () { loadLeads(n); };
        }(i)));
        pager.appendChild(b);
      }
    }
  }

  function formatDate(sql) {
    var d = new Date(String(sql).replace(' ', 'T') + 'Z');
    return isNaN(d) ? sql : d.toLocaleString('fr-FR');
  }

  ['#lead-search', '#lead-statut', '#lead-category'].forEach(function (sel) {
    $(sel).addEventListener('input', function () {
      window.clearTimeout(leadTimer);
      leadTimer = window.setTimeout(function () { loadLeads(1); }, 250);
    });
  });

  /* ---------------- reprise des demandes de l'ancien stockage local ------- */
  (function localLeads() {
    var raw;
    try { raw = localStorage.getItem('alamstores.leads'); } catch (e) { return; }
    if (!raw) { return; }
    var list;
    try { list = JSON.parse(raw); } catch (e) { return; }
    if (!Array.isArray(list) || !list.length) { return; }

    $('#local-note').hidden = false;
    $('#local-count').textContent = list.length;
    $('#local-export').addEventListener('click', function () {
      var cols = ['timestamp', 'statut', 'company', 'category', 'firstname', 'lastname',
                  'email', 'phone', 'address', 'zip', 'city', 'country', 'message'];
      var lines = [cols.join(';')].concat(list.map(function (l) {
        return cols.map(function (c) {
          var v = String(l[c] == null ? '' : l[c]).replace(/"/g, '""');
          return '"' + v + '"';
        }).join(';');
      }));
      var blob = new Blob(['﻿' + lines.join('\r\n') + '\r\n'],
                          { type: 'text/csv;charset=utf-8;' });
      var a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'devis-locaux.csv';
      a.click();
      URL.revokeObjectURL(a.href);
    });
  }());

  reset();            // dessine les zones média vides avant la première réponse
  resetCategoryForm();
  loadCategories();   // enchaîne sur loadProducts() : même source de catégories
  loadLeads();
}());
</script>
<?php endif; ?>
</body>
</html>
