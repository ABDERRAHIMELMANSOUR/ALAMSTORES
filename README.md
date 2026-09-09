# Alam Stores — site statique + back-office

Static HTML/CSS/JS rebuild of the `alamstores.ma` WordPress installation, plus
a small PHP/MySQL back-office for the product catalogue and the incoming quote
requests. No WordPress, no plugins, no theme — the public pages stay flat HTML
and the dynamic part is confined to two folders.

```
index.html                 Accueil
societe.html               … 24 further pages, one per original URL
404.html
admin.html                 redirect to /admin/ (old bookmark)
sitemap.xml  robots.txt  .htaccess

assets/
  css/main.min.css         one stylesheet
  js/main.min.js           one script
  images/                  871 files as YYYY/MM/ + hero/ carousel photos
  uploads/                 photos and PDFs added from the back-office
  docs/                    fiches techniques (PDF)
  fonts/                   4 files, Plus Jakarta Sans (84 KB total)

admin/index.php            the back-office (login required)
api/                       JSON endpoints — catalogue, leads, admin CRUD
db/schema.sql              database schema + seed_categories.sql

tools/                     build scripts + page copy (not web content)
SECURITY-AUDIT.md          malware findings from the WordPress export
```

**Installing this on a server?** `INSTALLATION.md` is the step-by-step guide,
in French — cPanel, FileZilla, the database and the web installer.

**The public pages work with or without the back-end.** Each category page
carries the catalogue that was baked in at build time, then refreshes it from
`api/catalog.php` on load. If PHP or the database is unavailable the fetch
fails silently and the built-in cards stay on screen — the site never breaks
because the back-office is down.

## Design system

The front end is a purpose-built design system, not the old WordPress theme.
`tools/data/design-system.css` holds the whole thing: colour and spacing
tokens, typography, and every component. Icons are inline SVG
(`tools/icons.py`), so no icon font is loaded.

| | |
|---|---|
| Brand | `#7d0e7c` refined into a 50→800 scale, paired with deep slate neutrals and soft off-whites |
| Type | Plus Jakarta Sans, self-hosted, fluid `clamp()` scale |
| Shape | 6→28px radius scale, layered soft shadows, generous whitespace |
| Motion | Hover lifts, image zoom, scroll reveal — all disabled under `prefers-reduced-motion` |

What that replaced: the `constrau` theme stylesheet, Bootstrap 4, Font Awesome,
ten Flaticon sets, Elegant Icons and Material Design Iconic — **525 KB of CSS
and 1.9 MB of font files, now 37 KB and 84 KB.**

### Layout and UX

- **Sticky header** that turns to frosted glass on scroll and carries the only
  "Devis gratuit" button, over a dark top bar showing address, phone and hours.
- **Slide-out drawer** on mobile with accordion submenus, focus trapping,
  Escape to close, and contact details plus both CTAs pinned to its foot.
- **Home hero** with a cross-fading photo slider on a 5-second auto-advance,
  trust badges and a single "Demander un devis" CTA.
- **Category cards** with hover zoom, a category tag and a single "Découvrir"
  affordance — the whole card is the link.
- **Partner logos** as an infinite, continuously scrolling marquee (pure CSS,
  pauses on hover, falls back to a plain scroll strip under
  `prefers-reduced-motion`).
- **Product cards** fed by the database, with a play badge over the photo when
  a video is attached, a PDF download when there is a fiche technique, and
  sub-products nested under **Déclinaisons**.
- **B2B quote form** with a showroom info card, conditional company field and
  inline validation; each submission goes to the database, to a local log, and
  then to WhatsApp pre-filled.
- **Floating WhatsApp bubble**, fixed bottom-right and visible through the whole
  scroll, above the scroll-to-top button.

## What carried over from WordPress

| Original | Where it went |
|---|---|
| `wp-content/uploads` media | `assets/images/` |
| Page list + per-page images | recovered from the Rank Math sitemap cache |
| URL structure | unchanged — one `.html` per original URL |
| Logo, brand colour `#7d0e7c` | `assets/images/2019/05/Logo-stores-rideaux-maroc.png`, sampled from it |
| Partner logos | `PARTNER_LOGOS` in `tools/build_pages.py` |

The theme's own stylesheets were used for the first conversion and have since
been replaced by the design system above.

### What did not

**Page body copy.** It lived in the WordPress MySQL database, which was not
part of the export, so the original wording could not be recovered. Every page
now carries **newly written French copy** held in `tools/content_fr.py`.

> ⚠️ That text is not your original wording. It describes each product category
> generically and deliberately avoids any claim that could not be verified —
> no founding year, project counts, certifications, guarantees or prices.
> **Review it before publishing.** If the live site ever becomes reachable,
> `tools/fetch_content.py` replaces it with the real copy in one command.

**Server-side features.** WooCommerce, Contact Form 7 / WPForms, Jetpack,
Elementor's runtime and the login system are gone by design.

## Showroom contact details

All contact data lives in `CONTACT` at the top of `tools/build_pages.py` and
feeds the top bar, drawer, footer, showroom card and every WhatsApp / e-mail
payload:

| Key | Value |
|---|---|
| `address` | Hay Nahda 1 Grp. AlAhd N° 1042 Rabat, Maroc |
| `address_short` | Hay Nahda 1, Rabat *(top bar only)* |
| `phone_display` | 05 37 75 97 72 |
| `phone_tel` | `+212537759772` *(used in `tel:` links)* |
| `whatsapp` | `212600055562` *(the mobile line — digits only, no `+`, no spaces)* |
| `whatsapp_display` | 06 00 05 55 62 *(shown wherever WhatsApp is named)* |
| `email` | contact@alamstores.ma |
| `linkedin` | https://www.linkedin.com/company/alam-stores/ |
| `hours_week` | Lun - Ven : 8:30 - 12:30 et 14:30 - 18:30 |
| `hours_sat` | Sam : 8:30 - 13:00 |

Edit them, then re-run `python3 tools/build_pages.py` to push the change through
all 26 pages.

> **Two numbers, on purpose.** `phone_display` is the showroom landline, used
> for `tel:` links. `whatsapp` / `whatsapp_display` is the mobile line, used by
> the floating bubble, the quote form and every place WhatsApp is named. They
> are separate keys, so changing one never touches the other.

## Calls to action

Every quote CTA points at `devis.html`. There is exactly **one** on a page —
"Devis gratuit" in the header — plus the hero and CTA-band buttons further down.
The top bar carries no button any more (address, phone and hours only), and the
category cards carry no "Devis" button either: the whole card is the link to its
page, ending in "Découvrir →".

WhatsApp appears in two places, both deliberate:

- the **floating bubble**, bottom right, fixed through the whole scroll — the
  permanent shortcut to a human;
- the end of the quote form, which hands the completed request to WhatsApp.

It also shows as a **contact channel** in the contact lists and the showroom
card, next to the address, phone, e-mail and opening hours — a labelled row
showing the number, not a button.

`motorisations-automatismes.html` is no longer in the navbar or the mobile
drawer. The page is untouched at its original URL, stays in `sitemap.xml`, and
is still linked from the footer — drop the slug back into `NAV_TOP` in
`tools/build_pages.py` to restore it.

The "Galerie / Nos réalisations" section has been removed from every page. The
builder still has `gallery_html()` if you ever want it back; nothing calls it.

## The B2B quote form

`devis.html` opens with a showroom card (address, phone + WhatsApp, opening
hours) and then a single clean form aimed at both retail and trade buyers.

**Votre projet** — Statut professionnel (Particulier / Entreprise ·
Professionnel (B2B) / Architecte · Revendeur), Catégorie, Message.
Choosing either professional status reveals a **Nom de l'entreprise** field and
makes it required; switching back to Particulier hides and clears it.

**Vos coordonnées** — Prénom, Nom, E-mail, Téléphone, Pays (defaults to Maroc),
Ville, Adresse, Code postal.

**Envoyer ma demande** does two things: it appends the submission to a local
lead log, then sends the visitor straight to WhatsApp — `https://wa.me/<number>`
with the whole request pre-filled as the message, ready to send in one tap. The
number comes from the `data-whatsapp` attribute on the form, which the builder
fills from `CONTACT["whatsapp"]`.

Every filled field is in that message, in order and labelled — statut,
entreprise, catégorie, prénom, nom, e-mail, téléphone, adresse, code postal,
ville, pays, then the message itself. Labels are wrapped in `*…*` so WhatsApp
renders them bold. Empty optional fields are left out rather than sent blank.

If the redirect is blocked — a pop-up blocker, an in-app browser — the status
line under the button keeps an **Ouvrir WhatsApp** link carrying the same
pre-filled URL, so the visitor still gets through in one tap. Visitors without
WhatsApp have the e-mail address right below, in the form note.

Categories in the form are wider than the page tree on purpose — `Rideaux` and
`Autre / Projet mixte` are offered without having a dedicated page. Edit
`FORM_CATEGORIES` and `STATUTS` in `tools/build_pages.py`.

## Leads — "Devis reçus"

Every submission is written to the `leads` table by `api/lead.php`, e-mailed to
every address in `notify_email` (`contact@alamstores.ma` and
`kassettebrahim.1997@gmail.com` by default — add or remove them in
`api/config.php`), and listed in the **Devis reçus** tab of the back-office: search, status and category filters,
unread count, mark as read, delete, and a CSV export (semicolons plus a BOM, so
Excel opens it with the accents intact). Every field is stored — timestamp,
statut, entreprise, catégorie, prénom, nom, e-mail, téléphone, adresse, code
postal, ville, pays and the message.

The browser sends it with `navigator.sendBeacon`, which the browser queues and
delivers even though the page is navigating to WhatsApp a moment later. A normal
`fetch` would be cancelled by that navigation.

Three destinations, in order of reliability:

1. **the database** — shared, survives the visitor's device, this is your register;
2. **`localStorage` in the visitor's browser** — a local trace, kept as a
   fallback for the case where PHP is unavailable;
3. **WhatsApp** — what the visitor actually sees and sends.

If the API is unreachable the first step fails silently: the visitor still gets
their WhatsApp message, and you still get the conversation. You lose the
database row, not the lead.

> Leads recorded before the database existed are still in the browser that
> submitted them. The dashboard notices them and offers a CSV export.

## Back-office (`/admin/`)

`admin/index.php` maintains the catalogue and reads the quote requests. It
needs PHP 7.4+ and MySQL 5.7+ / MariaDB 10.3+ — the same stack the WordPress
site ran on.

### Installation

**On a shared host, without SSH** — upload the files, then open
`https://votre-domaine/admin/setup.php`. It asks for the database credentials,
writes `api/config.php`, imports the schema, creates the first account, then
**deletes itself**. `INSTALLATION.md` walks through it screen by screen,
including the cPanel and FileZilla steps.

**With SSH**, the same thing by hand:

```bash
# 1. the database
mysql -u <user> -p <base> < db/schema.sql
mysql -u <user> -p <base> < db/seed_categories.sql

# 2. the credentials  (this file is gitignored and blocked by .htaccess)
cp api/config.sample.php api/config.php
chmod 640 api/config.php
php -r "echo bin2hex(random_bytes(32));"     # -> ip_salt

# 3. the first account (the password is typed, never passed as an argument)
php tools/make_admin.php votre-identifiant
```

Then open `https://votre-domaine/admin/`.

> `admin/setup.php` only works while no account exists, and returns 403 the
> moment one does. Delete it anyway once you are in — `tools/security_scan.py`
> reports it as a finding until you do.

`db/seed_categories.sql` is generated from the page tree by
`python3 tools/build_sql.py`, so **a category slug in the database is a page
slug on the site** — that is the whole mechanism that puts a product on the
right page. Re-run both after adding a page; the seed is an idempotent upsert,
replaying it never loses products.

### What you can enter

| Champ | Obligatoire | Ce qui apparaît sur le site |
|---|---|---|
| Titre | oui | le titre de la fiche |
| Catégorie | oui | la page où la fiche apparaît |
| Description | oui | le paragraphe sous le titre |
| Photos | oui, au moins une | la photo de la carte |
| Sous-produit de | non | range la fiche sous un produit principal |
| Lien vidéo YouTube | non | un bouton ▶ sur la photo, qui ouvre une fenêtre |
| Fiches techniques (PDF) | non | un bouton de téléchargement |
| Caractéristiques | non | le tableau intitulé / valeur |
| Visibilité | — | brouillon = invisible sur le site |

Sub-products nest one level deep, under **Déclinaisons** on the parent card.
The API refuses a sub-product of a sub-product rather than letting the tree run
away.

**Videos are never loaded before the click.** Only the 11-character YouTube id
is stored and published; the player URL is rebuilt from it, so nothing in the
catalogue can inject parameters into the `<iframe>`. Opening a video builds a
`youtube-nocookie.com` player on the spot, and closing it removes the frame,
which stops playback and drops the connection. A page nobody clicks makes zero
requests to YouTube.

### Publishing to the static build

The pages read the database live, so a change is visible immediately. To also
bake the catalogue into the HTML — better for search engines, and it keeps the
products visible if PHP ever stops:

1. **Exporter products.json** in the dashboard.
2. Replace `tools/data/products.json` with the downloaded file.
3. `python3 tools/build_pages.py`, then publish.

### Uploads

Photos and PDFs land in `assets/uploads/`, renamed at random. `api/admin/upload.php`
checks the real MIME type with `finfo` (not the browser's claim), re-checks
images with `getimagesize`, checks the `%PDF-` magic bytes, and refuses any file
containing `<?php`. On top of that, `assets/uploads/.htaccess` switches the PHP
engine off and remaps every executable extension to `text/plain`, so a file that
somehow got through would still be served as text. That is the exact path that
compromised the old WordPress site — see `SECURITY-AUDIT.md` §1.1.

## Sécurité du back-office

Everything that touches the database or a session is centralised in
`api/bootstrap.php`:

- **SQL** — PDO with `ATTR_EMULATE_PREPARES = false`; every value is bound, no
  query is built by concatenation.
- **Mots de passe** — `password_hash()` only. Five failures lock an account for
  fifteen minutes; a wrong username is verified against a dummy hash so the
  response takes the same time either way, and nothing distinguishes "unknown
  user" from "wrong password".
- **Sessions** — cookie `HttpOnly`, `SameSite=Strict`, `Secure` over HTTPS,
  regenerated on login, expired after two hours of inactivity.
- **CSRF** — every write carries a token compared with `hash_equals`.
- **En-têtes** — CSP, `nosniff`, `X-Frame-Options`, `Referrer-Policy`,
  `Permissions-Policy`, HSTS over HTTPS.
- **Formulaire public** — honeypot field, five submissions per hour per device
  (the IP is stored as a truncated HMAC, never in clear), origin check.
- **Export CSV** — a cell starting with `= + - @` is prefixed so Excel treats it
  as text rather than a formula.

### Le scanner anti-webshell

```bash
python3 tools/security_scan.py --baseline    # une fois, sur une install saine
python3 tools/security_scan.py               # ensuite, régulièrement
```

It flags executable files outside `api/`, `admin/` and `tools/`, the classic
backdoor signatures (eval on request data, base64/gzinflate chains, `parse_str`
decoders, mixed-case `<?phP` tags written to dodge a grep), oversized encoded
payloads, and any PHP file added, modified or missing since the baseline. Exit
code 1 when something needs looking at, so it drops straight into cron:

```cron
0 4 * * 1 cd /home/site && python3 tools/security_scan.py --quiet
```

## Home carousel

The hero is a pure image carousel — no video, no placeholder. (The only video
on the site is the product pop-up, and it is built on click, never before.)
`tools/build_hero.py` re-encodes five hand-picked, high-resolution
Alam Stores product photos to 1920x1080 WebP in `assets/images/hero/`
(904 KB total; only the first slide loads eagerly, at 156 KB). Swap the
`SOURCES` list in that script and re-run it, then update `HERO_IMAGES` in
`tools/build_pages.py` with the new filenames and alt text.

## Restoring the original page text

Run this from any machine that can reach the live site:

```bash
python3 tools/fetch_content.py                  # all 25 pages
python3 tools/fetch_content.py pergolas devis   # selected pages
python3 tools/fetch_content.py --dry-run        # preview, write nothing
python3 tools/fetch_content.py --base https://staging.example.com
```

For each page it fetches the live URL, extracts the main content region, strips
scripts / iframes / inline event handlers / the injected SEO-spam block
(see `SECURITY-AUDIT.md`), downloads any image not already present, rewrites
`wp-content/uploads` paths to `assets/images` and internal links to the local
`.html` files, then **replaces the written copy** with the original.
Standard library only.

Afterwards re-run `python3 tools/build_css.py` so the CSS purge accounts for
the restored markup.

If the site is already offline, restore the database to any WordPress instance
and point `--base` at it.

## Rebuilding

```bash
python3 tools/build_pages.py     # regenerate the HTML from the page model
python3 tools/build_css.py       # reassemble, purge and minify the stylesheet
node  tools/data/minify-js.js assets/js/main.js assets/js/main.min.js
python3 tools/build_sql.py       # regenerate db/seed_categories.sql from PAGES
```

`build_css.py` and the JS minifier need `clean-css`, `purgecss` and `terser`;
point `NODE_TOOLS` at the directory holding their `node_modules`:

```bash
npm install clean-css purgecss terser
NODE_TOOLS="$PWD" python3 tools/build_css.py
```

Where to edit what:

| Change | File |
|---|---|
| Navigation, page tree, page titles | `PAGES`, `NAV_TOP`, `NAV_LABELS` in `tools/build_pages.py` |
| Contact details | `CONTACT` in `tools/build_pages.py` |
| Page copy | `tools/content_fr.py` |
| Partner logos | `PARTNER_LOGOS` in `tools/build_pages.py` |
| Hero carousel photos | `SOURCES` in `tools/build_hero.py`, then `HERO_IMAGES` |
| Products, sub-products, media, fiches techniques | the back-office at `/admin/` (database) |
| Product fallback baked into the HTML | `tools/data/products.json`, exported from the back-office |
| Category tree in the database | `PAGES` in `tools/build_pages.py`, then `tools/build_sql.py` |
| Quote form statuses / categories | `STATUTS`, `FORM_CATEGORIES` in `tools/build_pages.py` |
| Colours, type, components | `tools/data/design-system.css` |
| Icons | `tools/icons.py` |

Re-run `build_pages.py` after any of the first four, and `build_css.py` after
touching the stylesheet.

## Front-end notes

`assets/js/main.js` (10.5 KB minified, 3.8 KB gzip) is the only script and has
no dependencies. It replaces the jQuery + Bootstrap + select2 + prettyPhoto +
owl-carousel stack the theme used to load (~300 KB) and handles: sticky/glass
header, drawer menu, hero slider, partner slider, lightbox, scroll reveal,
scroll-to-top and the B2B quote form. The partner marquee is pure CSS.

Accessibility: skip link, `aria-current` on the active nav item, focus trapping
in the drawer and lightbox, Escape to close both, keyboard and swipe navigation
in the gallery, visible focus rings, and `prefers-reduced-motion` honoured
throughout.

Performance: no external requests at runtime, lazy-loaded images below the fold,
`fetchpriority="high"` on the first hero slide, and self-hosted fonts with
`font-display: swap`.

Responsive: mobile-first, verified with no horizontal overflow from 320px to
1920px.

## Local preview

```bash
php -S 127.0.0.1:8000            # site + back-office + API
python3 -m http.server 8000      # static pages only, no back-office
```

Open <http://127.0.0.1:8000/>. Extensionless URLs (`/pergolas`) only work
through the `.htaccess` rules on a real Apache/LiteSpeed host; the local
preview uses the `.html` filenames, which is what every internal link points at.

`php -S` ignores `.htaccess`, so it is fine for trying the back-office but says
nothing about whether your hardening rules are in force — check those on the
real host.

## Before going live

1. Work through §4 of `SECURITY-AUDIT.md` — the live server still needs cleaning.
2. Rotate the credentials named in §3; `wp-config.php` was committed in plaintext
   and remains in git history.
3. Install the back-office: import `db/schema.sql` and `db/seed_categories.sql`,
   create `api/config.php` from the sample with a database user restricted to
   that one schema, generate an `ip_salt`, and create your account with
   `php tools/make_admin.php`. Then run `python3 tools/security_scan.py --baseline`
   on the freshly deployed tree and put the scan in cron.
4. Confirm the WhatsApp number: `212537759772` is the showroom landline, which
   only works on WhatsApp Business with voice verification (see above). Put a
   mobile in `CONTACT["whatsapp"]` if that is not set up.
5. Read through the copy in `tools/content_fr.py` and adjust it to how you
   actually describe your work — or restore the originals with
   `tools/fetch_content.py`.
6. Check the partner logos on `partenaires.html`: they come from your own media
   library, but confirm you still have permission to display each one.
