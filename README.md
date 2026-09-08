# Alam Stores — static site

Static HTML/CSS/JS conversion of the `alamstores.ma` WordPress installation.
No PHP, no database, no WordPress. Deploy by copying the repository root to the
document root.

```
index.html                 Accueil
societe.html               … 24 further pages, one per original URL
404.html
admin.html                 product / datasheet dashboard (not indexed)
sitemap.xml  robots.txt  .htaccess

assets/
  css/main.min.css         one stylesheet  (37 KB → 7.9 KB gzip)
  js/main.min.js           one script      (10.5 KB → 3.8 KB gzip)
  images/                  871 files as YYYY/MM/ + hero/ carousel photos
  docs/                    fiches techniques (PDF)
  fonts/                   4 files, Plus Jakarta Sans (84 KB total)

tools/                     build scripts + page copy (not web content)
SECURITY-AUDIT.md          malware findings from the WordPress export
```

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

- **Sticky header** that turns to frosted glass on scroll, over a dark top bar
  carrying the phone number and a WhatsApp button.
- **Slide-out drawer** on mobile with accordion submenus, focus trapping,
  Escape to close, and contact details plus both CTAs pinned to its foot.
- **Home hero** with a cross-fading photo slider on a 5-second auto-advance,
  trust badges and dual CTAs.
- **Category cards** with hover zoom, a category tag and a per-product
  "Demander" button that opens WhatsApp pre-filled with that product name.
- **Gallery tiles** on a 4:3 grid: photos fill the tile, while logos, banners
  and portraits are letterboxed on a padded card instead of being cropped.
- **Partner logos** as an infinite, continuously scrolling marquee (pure CSS,
  pauses on hover, falls back to a plain scroll strip under
  `prefers-reduced-motion`).
- **B2B quote form** with a showroom info card, conditional company field and
  inline validation, sending to WhatsApp or e-mail.
- **Floating WhatsApp button** and scroll-to-top on every page.

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
| `whatsapp` | `212537759772` *(digits only, no `+`, no spaces)* |
| `email` | contact@alamstores.ma |
| `hours_week` | Lun - Ven : 8:30 - 12:30 et 14:30 - 18:30 |
| `hours_sat` | Sam : 8:30 - 13:00 |

Edit them, then re-run `python3 tools/build_pages.py` to push the change through
all 26 pages.

> **WhatsApp on a landline.** `212537759772` is the showroom's fixed line.
> WhatsApp normally requires a mobile number; a landline only works if the
> number is registered with **WhatsApp Business** and verified by voice call.
> If that has not been set up, put your mobile in `whatsapp` — it is a separate
> key from `phone_tel`, so the displayed phone number does not change.

## The B2B quote form

`devis.html` opens with a showroom card (address, phone + WhatsApp, opening
hours) and then a single clean form aimed at both retail and trade buyers.

**Votre projet** — Statut professionnel (Particulier / Entreprise ·
Professionnel (B2B) / Architecte · Revendeur), Catégorie, Message.
Choosing either professional status reveals a **Nom de l'entreprise** field and
makes it required; switching back to Particulier hides and clears it.

**Vos coordonnées** — Prénom, Nom, E-mail, Téléphone, Pays (defaults to Maroc),
Ville, Adresse, Code postal.

The site is static, so nothing is stored or transmitted by the page. Both
buttons build the same structured payload — statut, entreprise, catégorie,
name, e-mail, phone, full postal address and the message — and hand it off:

- **Envoyer sur WhatsApp** opens `https://wa.me/212537759772` with the message
  pre-filled, in a new tab.
- **Envoyer par e-mail** opens the visitor's mail client via `mailto:`, with
  the subject line carrying the category and the company (or contact) name.

Categories in the form are wider than the page tree on purpose — `Rideaux` and
`Autre / Projet mixte` are offered without having a dedicated page. Edit
`FORM_CATEGORIES` and `STATUTS` in `tools/build_pages.py`.

Product cards across the site also carry a "Demander" button that opens
WhatsApp pre-filled with that product's name.

To post to a form service such as Formspree or Netlify Forms instead, add an
`action` to the `<form>` and drop the `data-whatsapp` / `data-mailto`
attributes.

## Product dashboard (`admin.html`)

A dependency-free browser tool for maintaining the product catalogue —
title, category, description, photos, video link, fiche technique (PDF) and a
free-form specs table.

```bash
python3 -m http.server 8000     # then open http://127.0.0.1:8000/admin.html
```

Because the site is static, the page cannot write to the server. It edits in
memory, keeps an unexported draft in `localStorage`, and publishes like this:

1. Add or edit fiches, then **Exporter products.json**.
2. Replace `tools/data/products.json` with the downloaded file.
3. Run `python3 tools/build_pages.py` and publish.

Products appear as a **Nos modèles** section on their category page, with the
photo, description, specs table, a **Fiche technique** download button and a
WhatsApp "Demander un prix" button.

Put PDFs in `assets/docs/` and reference them as
`assets/docs/<file>.pdf`; the download button only renders when the file
actually exists, so a typo fails visibly at build time rather than shipping a
dead link.

> **Video links, not embeds.** The dashboard stores a video URL per product and
> the page renders it as a plain outbound "Voir la vidéo" link. Nothing is
> embedded — the site still contains no `<video>`, `<iframe>`, `<embed>` or
> `<object>` anywhere, per the earlier brief. Say the word if you would rather
> have real embedded players.

`admin.html` is `noindex` and disallowed in `robots.txt`. It has **no access
control** — it is an authoring tool, not a protected admin area. It cannot
change anything server-side, but if you would rather it were not reachable at
all, delete it from the deployed copy and run it locally.

## Home carousel

The hero is a pure image carousel — **there is no video anywhere on the site**,
by design. `tools/build_hero.py` re-encodes five hand-picked, high-resolution
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
| Products / fiches techniques | `admin.html`, saved to `tools/data/products.json` |
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
python3 -m http.server 8000
```

Open <http://127.0.0.1:8000/>. Extensionless URLs (`/pergolas`) only work
through the `.htaccess` rules on a real Apache/LiteSpeed host; the local
preview uses the `.html` filenames, which is what every internal link points at.

## Before going live

1. Work through §4 of `SECURITY-AUDIT.md` — the live server still needs cleaning.
2. Rotate the credentials named in §3; `wp-config.php` was committed in plaintext
   and remains in git history.
3. Confirm the WhatsApp number: `212537759772` is the showroom landline, which
   only works on WhatsApp Business with voice verification (see above). Put a
   mobile in `CONTACT["whatsapp"]` if that is not set up.
4. Read through the copy in `tools/content_fr.py` and adjust it to how you
   actually describe your work — or restore the originals with
   `tools/fetch_content.py`.
5. Check the partner logos on `partenaires.html`: they come from your own media
   library, but confirm you still have permission to display each one.
