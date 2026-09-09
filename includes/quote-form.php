<?php
/**
 * Formulaire de devis. Généré par tools/build_pages.py — ne pas éditer ici.
 * Les listes déroulantes viennent de STATUTS et FORM_CATEGORIES.
 */
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/recaptcha.php';
$flash = alam_flash();
?>
  <section class="section" style="padding-top:clamp(24px,3vw,36px)">
    <div class="shell">
      <form class="form-card" id="devis-form" data-quote-form novalidate
            method="post" action="api/lead.php" data-whatsapp="212600055562">
        <div class="form-card__head">
          <h2>Demande de devis</h2>
          <p>Particuliers, entreprises, architectes et revendeurs : décrivez votre
             projet et nous revenons vers vous avec une proposition chiffrée.
             Votre demande est mise en forme dans WhatsApp&nbsp;: vous la relisez
             et vous l'envoyez d'un seul geste.</p>
        </div>

        <div class="form-card__body">
<?php if (!empty($flash['message'])): ?>
          <p class="form-status is-error" role="alert"><?= alam_e($flash['message']) ?></p>
<?php endif; ?>
          <fieldset class="fieldset">
            <legend class="fieldset__title">Votre projet</legend>
            <p class="fieldset__hint">Ces trois champs nous suffisent pour vous orienter.</p>
            <div class="field-grid">
              <div class="field">
                <label for="f-statut">Statut professionnel <span class="req" aria-hidden="true">*</span></label>
                <select id="f-statut" name="statut" required data-statut><option value="Particulier"<?= alam_selected('statut', 'Particulier') ?>>Particulier</option><option value="Entreprise / Professionnel (B2B)" data-b2b="1"<?= alam_selected('statut', 'Entreprise / Professionnel (B2B)') ?>>Entreprise / Professionnel (B2B)</option><option value="Architecte / Revendeur" data-b2b="1"<?= alam_selected('statut', 'Architecte / Revendeur') ?>>Architecte / Revendeur</option></select>
                <span class="field__error" data-error-for="statut"><?= alam_err('statut') ?></span>
              </div>
              <div class="field">
                <label for="f-category">Catégorie <span class="req" aria-hidden="true">*</span></label>
                <select id="f-category" name="category" required><option value="Stores Extérieurs"<?= alam_selected('category', 'Stores Extérieurs') ?>>Stores Extérieurs</option><option value="Stores Intérieurs"<?= alam_selected('category', 'Stores Intérieurs') ?>>Stores Intérieurs</option><option value="Pergolas"<?= alam_selected('category', 'Pergolas') ?>>Pergolas</option><option value="Moustiquaires"<?= alam_selected('category', 'Moustiquaires') ?>>Moustiquaires</option><option value="Rideaux"<?= alam_selected('category', 'Rideaux') ?>>Rideaux</option><option value="Stores Enrouleurs"<?= alam_selected('category', 'Stores Enrouleurs') ?>>Stores Enrouleurs</option><option value="Stores Vénitiens"<?= alam_selected('category', 'Stores Vénitiens') ?>>Stores Vénitiens</option><option value="Stores Californiens"<?= alam_selected('category', 'Stores Californiens') ?>>Stores Californiens</option><option value="Stores Bateaux"<?= alam_selected('category', 'Stores Bateaux') ?>>Stores Bateaux</option><option value="Panneaux Japonais"<?= alam_selected('category', 'Panneaux Japonais') ?>>Panneaux Japonais</option><option value="Toiles Tendues"<?= alam_selected('category', 'Toiles Tendues') ?>>Toiles Tendues</option><option value="Parasols"<?= alam_selected('category', 'Parasols') ?>>Parasols</option><option value="Abris de Voiture"<?= alam_selected('category', 'Abris de Voiture') ?>>Abris de Voiture</option><option value="Motorisations &amp; Automatismes"<?= alam_selected('category', 'Motorisations &amp; Automatismes') ?>>Motorisations &amp; Automatismes</option><option value="Autre / Projet mixte"<?= alam_selected('category', 'Autre / Projet mixte') ?>>Autre / Projet mixte</option></select>
                <span class="field__error" data-error-for="category"><?= alam_err('category') ?></span>
              </div>
              <div class="field field--full" data-b2b-field<?= alam_old('company') !== '' ? '' : ' hidden' ?>>
                <label for="f-company">Nom de l'entreprise <span class="req" aria-hidden="true">*</span></label>
                <input id="f-company" name="company" type="text" autocomplete="organization"
                       placeholder="Raison sociale, cabinet ou enseigne"
                       value="<?= alam_old('company') ?>">
                <span class="field__error" data-error-for="company"><?= alam_err('company') ?></span>
              </div>
              <div class="field field--full">
                <label for="f-message">Message <span class="req" aria-hidden="true">*</span></label>
                <textarea id="f-message" name="message" rows="5" required
                  placeholder="Nombre d'ouvertures, dimensions approximatives, pièce concernée, orientation, commande manuelle ou motorisée, délai souhaité…"><?= alam_old('message') ?></textarea>
                <span class="field__error" data-error-for="message"><?= alam_err('message') ?></span>
              </div>
            </div>
          </fieldset>

          <fieldset class="fieldset">
            <legend class="fieldset__title">Vos coordonnées</legend>
            <p class="fieldset__hint">Pour vous recontacter et, si besoin, planifier la prise de mesures.</p>
            <div class="field-grid">
              <div class="field">
                <label for="f-firstname">Prénom <span class="req" aria-hidden="true">*</span></label>
                <input id="f-firstname" name="firstname" type="text" required autocomplete="given-name"
                       value="<?= alam_old('firstname') ?>">
                <span class="field__error" data-error-for="firstname"><?= alam_err('firstname') ?></span>
              </div>
              <div class="field">
                <label for="f-lastname">Nom <span class="req" aria-hidden="true">*</span></label>
                <input id="f-lastname" name="lastname" type="text" required autocomplete="family-name"
                       value="<?= alam_old('lastname') ?>">
                <span class="field__error" data-error-for="lastname"><?= alam_err('lastname') ?></span>
              </div>
              <div class="field">
                <label for="f-email">E-mail <span class="req" aria-hidden="true">*</span></label>
                <input id="f-email" name="email" type="email" required autocomplete="email"
                       value="<?= alam_old('email') ?>">
                <span class="field__error" data-error-for="email"><?= alam_err('email') ?></span>
              </div>
              <div class="field">
                <label for="f-phone">Téléphone <span class="req" aria-hidden="true">*</span></label>
                <input id="f-phone" name="phone" type="tel" required autocomplete="tel"
                       value="<?= alam_old('phone') ?>">
                <span class="field__error" data-error-for="phone"><?= alam_err('phone') ?></span>
              </div>
              <div class="field">
                <label for="f-country">Pays</label>
                <input id="f-country" name="country" type="text" autocomplete="country-name"
                       value="<?= alam_old('country', 'Maroc') ?>">
                <span class="field__error" data-error-for="country"><?= alam_err('country') ?></span>
              </div>
              <div class="field">
                <label for="f-city">Ville</label>
                <input id="f-city" name="city" type="text" autocomplete="address-level2"
                       value="<?= alam_old('city') ?>">
                <span class="field__error" data-error-for="city"><?= alam_err('city') ?></span>
              </div>
              <div class="field">
                <label for="f-address">Adresse</label>
                <input id="f-address" name="address" type="text" autocomplete="street-address"
                       value="<?= alam_old('address') ?>">
                <span class="field__error" data-error-for="address"><?= alam_err('address') ?></span>
              </div>
              <div class="field">
                <label for="f-zip">Code postal</label>
                <input id="f-zip" name="zip" type="text" autocomplete="postal-code" inputmode="numeric"
                       value="<?= alam_old('zip') ?>">
                <span class="field__error" data-error-for="zip"><?= alam_err('zip') ?></span>
              </div>
            </div>
          </fieldset>

          <div class="field-grid">
<?php alam_recaptcha_widget(); ?>
          </div>

          <div class="form-nav">
            <button class="btn btn--primary btn--lg" type="submit">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.47 14.38c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.65-2.05-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.38-.27.3-1.04 1.01-1.04 2.47 0 1.46 1.06 2.87 1.21 3.07.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.22 1.36.19 1.87.12.57-.09 1.75-.72 2-1.41.25-.69.25-1.28.17-1.41-.07-.13-.27-.2-.57-.35zM12.04 2.5A9.46 9.46 0 0 0 4 16.86L2.5 21.5l4.77-1.5a9.46 9.46 0 1 0 4.77-17.5zm0 17.16a7.7 7.7 0 0 1-3.92-1.07l-.28-.17-2.9.91.93-2.83-.18-.29a7.7 7.7 0 1 1 6.35 3.45z"/></svg><span>Envoyer ma demande</span></button>
          </div>
          <p class="form-status" role="status" aria-live="polite"></p>
          <p class="form-note">Un r&eacute;capitulatif complet s'ouvre dans WhatsApp, pr&ecirc;t
             &agrave; envoyer au 06 00 05 55 62&nbsp;: vous relisez et vous gardez la main sur l'envoi.
             Pas de WhatsApp&nbsp;? &Eacute;crivez-nous &agrave;
             <a href="mailto:contact@alamstores.ma">contact@alamstores.ma</a>.</p>
        </div>
        <input type="text" name="website" tabindex="-1" autocomplete="off"
               aria-hidden="true" style="position:absolute;left:-9999px">
      </form>
    </div>
  </section>
