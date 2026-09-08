# Alam Stores — static site

Static HTML/CSS/JS conversion of the `alamstores.ma` WordPress installation.
No PHP, no database, no WordPress. Deploy by copying the repository root to the
document root.

```
index.html                 Accueil
societe.html               … 24 further pages, one per original URL
404.html
sitemap.xml  robots.txt  .htaccess

assets/
  css/main.min.css         one stylesheet  (37 KB → 7.9 KB gzip)
  js/main.min.js           one script      (10.5 KB → 3.8 KB gzip)
  images/                  871 files, organised as YYYY/MM/ like the originals
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
- **Home hero** with a cross-fading photo slider, trust badges
  (Devis gratuit / Installation rapide / Fabrication sur mesure) and dual CTAs.
- **Category cards** with hover zoom, a category tag and a per-product
  "Demander" button that opens WhatsApp pre-filled with that product name.
- **Partner logos** as a snap-scrolling slider with prev/next controls.
- **Quote form** in three steps with a progress indicator, inline validation
  and a review summary before sending.
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

## Contact details — replace these

Four placeholder values feed the footer, the quote form buttons and the contact
card. They live in one place, `CONTACT` at the top of `tools/build_pages.py`:

| Key | Placeholder | Notes |
|---|---|---|
| `phone_display` | `+212 6 00 00 00 00` | as shown on the page |
| `phone_tel` | `+212600000000` | used in `tel:` links |
| `whatsapp` | `212600000000` | **digits only**, no `+`, no spaces |
| `email` | `contact@alamstores.ma` | used in `mailto:` links |
| `address` | `Casablanca, Maroc` | city / full address |
| `hours` | `Lundi – Samedi, 9h – 19h` | opening hours |

Edit them, then re-run `python3 tools/build_pages.py` to push the change through
all 26 pages.

## How the quote form works

The site is static, so there is no server and no database. `devis.html` walks
the visitor through three steps — Projet, Détails, Coordonnées — validating as
it goes and showing a summary before sending. Nothing is stored or transmitted
by the page itself:

- **Envoyer sur WhatsApp** opens `https://wa.me/<number>` with the message
  pre-filled, in a new tab.
- **Par e-mail** opens the visitor's mail client via `mailto:` with the subject
  and body pre-filled.

Both build the same message from the form fields (name, e-mail, phone, city,
product, number of openings, room, orientation, command type, deadline and the
project description). Product cards across the site also carry a "Demander"
button that opens WhatsApp pre-filled with that product's name.

To post to a form service such as Formspree or Netlify Forms instead, add an
`action` to the `<form>` and drop the `data-whatsapp` / `data-mailto`
attributes.

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
| Colours, type, components | `tools/data/design-system.css` |
| Icons | `tools/icons.py` |

Re-run `build_pages.py` after any of the first four, and `build_css.py` after
touching the stylesheet.

## Front-end notes

`assets/js/main.js` (10.5 KB minified, 3.8 KB gzip) is the only script and has
no dependencies. It replaces the jQuery + Bootstrap + select2 + prettyPhoto +
owl-carousel stack the theme used to load (~300 KB) and handles: sticky/glass
header, drawer menu, hero slider, partner slider, lightbox, scroll reveal,
scroll-to-top and the three-step quote form.

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
3. Replace the `CONTACT` placeholders (above) with your real phone, WhatsApp
   number, e-mail and address.
4. Read through the copy in `tools/content_fr.py` and adjust it to how you
   actually describe your work — or restore the originals with
   `tools/fetch_content.py`.
5. Check the partner logos on `partenaires.html`: they come from your own media
   library, but confirm you still have permission to display each one.
