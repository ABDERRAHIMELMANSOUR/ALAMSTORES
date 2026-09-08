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
  css/main.min.css         one stylesheet  (202 KB → 37 KB gzip)
  js/main.min.js           one script      (6.3 KB → 2.5 KB gzip)
  images/                  871 files, organised as YYYY/MM/ like the originals
  fonts/                   56 self-hosted font files

tools/                     build + restore scripts (not web content)
SECURITY-AUDIT.md          malware findings from the WordPress export
```

## What carried over from WordPress

| Original | Where it went |
|---|---|
| Theme `constrau` stylesheets | concatenated into `assets/css/main.min.css` in the theme's own enqueue order |
| Theme customizer CSS | rendered by `tools/render_customizer.php` and appended, so the cascade matches |
| `wp-content/uploads` media | `assets/images/` |
| Page list + per-page images | recovered from the Rank Math sitemap cache |
| Logo, brand colour `#7d0e7c` | `assets/images/2019/05/Logo-stores-rideaux-maroc.png`, sampled from it |
| Fonts Lato + Rajdhani | self-hosted in `assets/fonts/` |

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

The site is static, so there is no server and no database. The form on
`devis.html` validates in the browser, then composes the request and hands it
off — nothing is stored or transmitted by the page itself:

- **Envoyer par WhatsApp** opens `https://wa.me/<number>` with the message
  pre-filled, in a new tab.
- **Envoyer par e-mail** opens the visitor's mail client via `mailto:` with the
  subject and body pre-filled.

Both buttons build the same message from the form fields (name, e-mail, phone,
city, product, number of openings, project description). If you would rather
post to a form service such as Formspree or Netlify Forms, add an `action` to
the `<form>` and drop the `data-whatsapp` / `data-mailto` attributes.

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

Editing navigation, page titles or the page tree is done in the `PAGES`,
`NAV_TOP` and `NAV_LABELS` tables at the top of `tools/build_pages.py`, then
re-running it. Page copy lives in `tools/content_fr.py`, partner logos in
`PARTNER_LOGOS`, and component styles in `tools/data/site-extra.css`.

## Front-end notes

The theme used to load jQuery, Bootstrap JS, select2, prettyPhoto, owl-carousel
and the theme script — roughly 300 KB. All of it is replaced by
`assets/js/main.js` (6.3 KB minified), which implements the same front-end
behaviour with no dependencies:

- collapsible mobile navigation and dropdown submenus
- hero slider with autoplay, dots, arrows and touch swipe
- image lightbox with keyboard and swipe navigation
- scroll-to-top, smooth in-page anchors, form validation

Bootstrap's **CSS** is kept, because the theme's stylesheet is built on its grid.

Accessibility and performance: skip link, `aria-current` on the active nav item,
focus management in the lightbox, `prefers-reduced-motion` honoured, lazy-loaded
images below the fold, `fetchpriority="high"` on the first hero slide, and no
external requests at runtime.

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
