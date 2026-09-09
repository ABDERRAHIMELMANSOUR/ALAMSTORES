# Security audit — alamstores.ma WordPress export

Audit performed while converting the WordPress installation in `ALAMSTORES/`
to the static site in this repository. **The installation was compromised.**
Every item below was removed from the static output; nothing from the
`wp-content/plugins`, `wp-admin`, `wp-includes` or `mu-plugins` trees survives
into the published site.

Source reviewed: WordPress 7.1, theme `constrau`, 27 plugins, `wp-content/uploads`.

---

## 1. Confirmed malicious code

### 1.1 SEO-spam injector masquerading as Wordfence — **critical**

| | |
|---|---|
| Path | `wp-content/plugins/footer-links-manager/footer-links-manager.php` (38 KB) |
| Config | `wp-content/plugins/footer-links-manager/links.json` (27 KB) |

The plugin header declares `Plugin Name: Wordfence` — it impersonates a
well-known security plugin. The code is commented in Russian and implements a
remote-controlled link injector:

- `FLM_CONFIG_URL` lets an operator serve link payloads from an external host;
  when empty it falls back to the bundled `links.json`.
- It hooks `wp_head` at `PHP_INT_MAX` **and** `wp_footer`, so the block renders
  even on themes that never call `wp_footer`.
- `links.json` is keyed by hostname and carries 21 spam blocks aimed at
  `alamstores.ma`, each with article text, a table, a list and a `{link}`
  placeholder pointing at gambling sites (Bizzo Casino, Richard Casino,
  Jokabet, Spinboss, Golden Crown and others) across English, French, Dutch
  and Polish.

This was actively injecting third-party gambling backlinks into the live site's
pages — a black-hat SEO parasite that puts the domain at risk of a manual
Google penalty. **Removed.**

### 1.2 Obfuscated PHP webshell droppers — **critical**

Staged archives found in the media library, all timestamped 2026-08-19:

```
wp-content/uploads/2026/08/gallery-1787139569.zip
wp-content/uploads/2026/08/gallery-1787139569-1.zip
wp-content/uploads/2026/08/gallery-1787139569-2.zip
wp-content/uploads/2026/08/gallery-1787139569-3.zip
wp-content/uploads/2026/08/custom_functions_1787139787.zip
```

Each contains a copy of the *wp-file-manager* plugin plus one extra 10,453-byte
file with a typosquatted name designed to blend into a theme directory:

- `archlve.php` — mimics `archive.php`
- `paqe.php` — mimics `page.php`

Both are the same payload: an HTML comment marker `<!--Kn9HVOb1-->`, a
mixed-case `<?phP` opening tag, then a `parse_str()`-based decoder assembled
from hundreds of URL-encoded hex fragments spliced together with junk comments
to defeat signature scanning. This is a full webshell. **Removed.**

### 1.3 Duplicated file-manager plugin under a random name — **high**

```
wp-content/plugins/widget_1787139992/
├── file_folder_manager.php   (87 KB — wp-file-manager)
├── styIe.php                 (0 bytes)
└── ...
```

A second, renamed copy of *wp-file-manager* — a classic persistence mechanism:
if the visible plugin is removed, the attacker retains an arbitrary file
read/write/upload UI. Note `styIe.php` uses a capital **I** in place of the
lowercase **l** in "style" to pass a casual directory listing.

The directory suffix `1787139992` is a Unix timestamp resolving to
**2026-08-19**, matching the dropper archives and the `.htaccess` backup date
below. **Removed.**

### 1.4 Emptied payload stubs — **medium**

```
wp-content/litespeed/comment_section_1787139953.php   0 bytes
wp-content/plugins/widget_1787139992/styIe.php        0 bytes
wp-content/mu-plugins/site-compat-layer.php           0 bytes
wp-content/mu-plugins/wp-compat-layer.php             0 bytes
goods.php                                             8 bytes  (`<?='';`)
shop.php                                              8 bytes  (`<?='';`)
```

Zero-byte and near-empty PHP files sitting in directories that should hold no
PHP at all. They follow the same `1787139xxx` timestamp naming as the confirmed
malware and are consistent with payloads that were truncated by a scanner but
left in place as re-infection targets. **Removed.**

### 1.5 Stripped `.htaccess` hardening — **medium**

`.htaccess-backup` contains a `# BEGIN Security Block` section that the live
`.htaccess` no longer has:

```apache
RewriteRule ^wp-admin/includes/ - [F,L]
RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
RewriteRule ^wp-includes/theme-compat/ - [F,L]
Options All -Indexes
Header always unset X-Powered-By
```

A sibling backup is dated `.htaccess.bak-20260820` — the day after the malware
timestamps. The protections were removed from the active config during the
compromise window. The static site's `.htaccess` restores equivalent hardening.

---

## 2. Reviewed and judged legitimate

These tripped heuristics during scanning but were verified as genuine:

| Item | Verdict |
|---|---|
| `wp-includes/compat-utf8.php` | Genuine WordPress core (added in 6.9). Not an injected file. |
| `wp-content/mu-plugins/sso-loader.php` | Standard host/panel single-sign-on helper (nonce + salt + 5-attempt lockout). Legitimate, but it is an admin-login bypass surface and has no place on a static site. Removed. |
| `wp-content/object-cache.php` | Performance Lab drop-in from the WordPress Performance Team. |
| `parse_str` / `eval` hits in Jetpack, Rank Math, Google Site Kit | Normal library code. |

---

## 3. Credential exposure — **action required**

`ALAMSTORES/wp-config.php` was committed to this repository in plaintext and
contains live secrets:

- database name, user and password
- all eight authentication keys and salts (`AUTH_KEY`, `SECURE_AUTH_KEY`,
  `LOGGED_IN_KEY`, `NONCE_KEY` and the four matching salts)

The file is deleted in this commit, **but it remains in the git history** and
must be treated as public. Regardless of the static migration:

1. Change the MySQL password for `alamstor_alamstores_user`.
2. Rotate the WordPress salts if any WordPress instance is still running
   anywhere against that database.
3. Rotate any hosting-panel credential that reuses the same password.
4. If this repository was ever pushed to a shared or public remote, treat the
   values as compromised and consider purging history
   (`git filter-repo --path ALAMSTORES/wp-config.php --invert-paths`).

---

## 4. Server-side cleanup still required

Removing these files from the repository does **not** clean the live server.
Before or alongside the static cutover:

1. Take a full backup of `public_html` and the database.
2. Delete the paths listed in sections 1.1–1.4 from the server.
3. Audit `wp_users` for unknown administrator accounts and `wp_options`
   for an `sso_token` value.
4. Search the database for injected content:
   `SELECT * FROM wpnq_posts WHERE post_content LIKE '%casino%' OR post_content LIKE '%flm-%';`
5. Check scheduled tasks: the injector registered a `flm_refresh_event` cron
   hook that re-pulls its remote config.
6. Rotate the credentials in section 3.
7. Once the static site is live, remove PHP execution from the document root
   entirely.

---

## 5. What the rebuild removes by construction

The public pages are HTML, CSS, JS, images and fonts only. No plugin loader, no
XML-RPC endpoint, no `wp-login.php`, no theme, no `wp-admin` — the entire class
of attack that produced the findings above is gone with the platform.

`assets/js/main.js` is the only JavaScript, it is first-party, and it loads no
third-party script. Fonts are self-hosted. The only external request the site
can make is the YouTube player, and only after a visitor clicks a product video.

## 6. The back-office — PHP, back on purpose

The product catalogue and the quote requests need to be stored somewhere shared,
so PHP and MySQL are back — but confined to `api/` and `admin/`, roughly 1 500
lines that can be read in a sitting, against the ~600 000 lines WordPress and
its plugins carried. Every finding in §1 is answered directly:

| Finding | What now prevents it |
|---|---|
| §1.1 webshell dropped through an upload | `api/admin/upload.php` checks the real MIME type with `finfo`, re-checks images with `getimagesize` and PDFs by their `%PDF-` magic bytes, rejects any file containing `<?php`, and renames everything at random. `assets/uploads/.htaccess` switches the PHP engine off and remaps executable extensions to `text/plain`. Verified: a webshell renamed `innocent.jpg` with `Content-Type: image/jpeg` is refused with 415. |
| §1.2 obfuscated droppers | `tools/security_scan.py` matches the exact signatures found here — `eval(base64_decode(…))`, `parse_str` decoders, mixed-case `<?phP` tags — and flags any PHP file added or changed since the baseline. |
| §1.3 plugin duplicated under a random name | There are no plugins. The scanner's manifest lists 11 PHP files; a twelfth is reported. |
| §1.5 stripped `.htaccess` hardening | The hardening is in the repository. A change to it shows up in `git status`; the scanner reads `.htaccess` files too. |
| §3 credentials in the repository | `api/config.php` is gitignored, denied by two `.htaccess` rules, and shipped only as `config.sample.php` with empty values. |

The rest of the surface is closed the ordinary way: PDO with real prepared
statements, `password_hash` with lockout after five failures, CSRF tokens on
every write, `HttpOnly`/`SameSite=Strict`/`Secure` session cookies regenerated
on login, a CSP that allows nothing but `self` and the YouTube frame, a honeypot
and per-device rate limit on the public form, and IP addresses stored only as a
truncated HMAC.

### Verified, not just asserted

The API was exercised end to end before shipping: wrong password rejected, write
without a CSRF token rejected (403), webshell-as-JPG rejected (415), image path
`../../etc/passwd` rejected (422), non-YouTube video URL rejected (422),
sub-product nesting capped, honeypot swallowed silently, CSV export carrying its
BOM. The one thing **not** verified here is the MySQL schema against a live
MySQL server — no MySQL was available in the build environment, so the API was
tested against an equivalent SQLite database. Import `db/schema.sql` on a test
database before pointing it at production.

### Still on you

Give the database user rights on that one schema only — no `FILE`, no `GRANT`,
no access to other databases. Serve `/admin/` over HTTPS. And run the scanner on
a schedule; a baseline taken today is what makes tomorrow's change visible:

```cron
0 4 * * 1 cd /home/site && python3 tools/security_scan.py --quiet
```
