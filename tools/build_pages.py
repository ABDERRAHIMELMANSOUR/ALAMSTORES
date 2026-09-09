#!/usr/bin/env python3
"""
Generate the Alam Stores static site.

Structure and data come from the original WordPress install:

  * page inventory and per-page images -> Rank Math sitemap cache
    (tools/data/page-sitemap.xml)
  * logo, media and partner logos      -> wp-content/uploads, now assets/images
  * URL structure                      -> unchanged, one .html per original URL

Presentation is a purpose-built design system (tools/data/design-system.css)
plus inline SVG icons (tools/icons.py); the legacy constrau/Bootstrap/icon-font
stack is gone.

Page copy lives in tools/content_fr.py. It was written for this rebuild and is
NOT the original wording, which was in the WordPress database and not part of
the export — review it before publishing. tools/fetch_content.py replaces it
with the originals if the live site becomes reachable.

Showroom contact details live in CONTACT below.

Usage:  python3 tools/build_pages.py
"""

import html
import json
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from content_fr import CONTENT  # noqa: E402
from icons import icon  # noqa: E402

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SITEMAP = os.path.join(ROOT, "tools", "data", "page-sitemap.xml")
PRODUCTS_JSON = os.path.join(ROOT, "tools", "data", "products.json")

SITE_NAME = "Alam Stores"
SITE_TAGLINE = "Le Spécialiste de l'Aménagement et de la Décoration"
SITE_URL = "https://alamstores.ma"
LOGO = "assets/images/2019/05/Logo-stores-rideaux-maroc.png"

# ---------------------------------------------------------------------------
# SHOWROOM CONTACT DETAILS
# These feed the top bar, the drawer, the footer, every contact card and the
# WhatsApp / e-mail payloads. Edit here, then re-run this script.
#   whatsapp: international format, digits only, no "+" and no spaces.
# ---------------------------------------------------------------------------
CONTACT = {
    "phone_display": "05 37 75 97 72",
    "phone_tel": "+212537759772",
    # WhatsApp runs on the mobile line, not the showroom landline — the two
    # numbers are shown separately everywhere they appear.
    "whatsapp": "212600055562",
    "whatsapp_display": "06 00 05 55 62",
    "email": "contact@alamstores.ma",
    "linkedin": "https://www.linkedin.com/company/alam-stores/",
    "address": "Hay Nahda 1 Grp. AlAhd N° 1042 Rabat, Maroc",
    "address_short": "Hay Nahda 1, Rabat",
    "hours": "Lun - Ven: 8:30 - 12:30 et 14:30 - 18:30 | Sam: 8:30 - 13:00",
    "hours_week": "Lun - Ven : 8:30 - 12:30 et 14:30 - 18:30",
    "hours_sat": "Sam : 8:30 - 13:00",
}

# Images referenced by the sitemap that no longer exist on disk, mapped to the
# closest surviving file in the same upload batch.
IMAGE_FALLBACKS = {
    "2022/04/139232154_778669192860628_7937894776157646481_n-640x480.jpg":
        "2022/04/139232154_778669192860628_7937894776157646481_n-1-640x480.jpg",
    "2022/04/261050820_3058944394325626_4750182805420232073_n-640x480.jpg":
        "2019/05/261050820_3058944394325626_4750182805420232073_n-min-640x480.webp",
    "2022/04/caduta.jpg": "2022/04/caduta-galer.jpg",
    "2022/04/japonais-8.jpg": "2019/05/japonais-8-1-min-1-1-e1690475674694.webp",
}

# slug -> (title, parent slug or None)
PAGES = [
    ("home",                       "Accueil",                     None),
    ("societe",                    "Société",                     None),
    ("stores-interieurs",          "Stores Intérieurs",           None),
    ("stores-enrouleurs",          "Stores Enrouleurs",           "stores-interieurs"),
    ("store-enrouleur-occultant",  "Store Enrouleur Occultant",   "stores-enrouleurs"),
    ("store-enrouleur-tamisant",   "Store Enrouleur Tamisant",    "stores-enrouleurs"),
    ("store-enrouleur-screen",     "Store Enrouleur Screen",      "stores-enrouleurs"),
    ("store-enrouleur-imprime",    "Store Enrouleur Imprimé",     "stores-enrouleurs"),
    ("stores-venitiens",           "Stores Vénitiens",            "stores-interieurs"),
    ("store-venitien-bois",        "Store Vénitien Bois",         "stores-venitiens"),
    ("store-venitien-aluminium",   "Store Vénitien Aluminium",    "stores-venitiens"),
    ("stores-californiens",        "Stores Californiens",         "stores-interieurs"),
    ("stores-bateaux",             "Stores Bateaux",              "stores-interieurs"),
    ("store-duo-jour-nuit",        "Store Duo Jour / Nuit",       "stores-interieurs"),
    ("panneaux-japonais",          "Panneaux Japonais",           "stores-interieurs"),
    ("stores-exterieurs",          "Stores Extérieurs",           None),
    ("pergolas",                   "Pergolas",                    "stores-exterieurs"),
    ("parasols",                   "Parasols",                    "stores-exterieurs"),
    ("toiles-tendues",             "Toiles Tendues",              "stores-exterieurs"),
    ("abris-de-voiture",           "Abris de Voiture",            "stores-exterieurs"),
    ("moustiquaires",              "Moustiquaires",               "stores-exterieurs"),
    ("motorisations-automatismes", "Motorisations & Automatismes", None),
    ("service",                    "Service",                     None),
    ("partenaires",                "Partenaires",                 None),
    ("devis",                      "Devis",                       None),
]

TITLES = {slug: title for slug, title, _ in PAGES}
PARENTS = {slug: parent for slug, _, parent in PAGES}

# Shorter labels used in the navigation bar only (page titles stay full).
NAV_LABELS = {"motorisations-automatismes": "Motorisations"}

# "motorisations-automatismes" is deliberately absent. The page still exists at
# its original URL and stays in sitemap.xml, but nothing links to it any more —
# neither the navbar nor the footer. Put the slug back here to bring it back.
NAV_TOP = ["home", "societe", "stores-interieurs", "stores-exterieurs",
           "service", "partenaires"]

# Pages that never carry a product catalogue — the same list drives
# tools/build_sql.py, so the database and the site agree on what a category is.
NON_CATALOGUE = {"home", "societe", "service", "partenaires", "devis"}

# Small tag shown on category cards.
CARD_TAGS = {"stores-interieurs": "Intérieur", "stores-exterieurs": "Extérieur"}

# Client and supplier logos from the site's own media library.
PARTNER_LOGOS = [
    ("2022/04/safran-logo.png", "Safran"),
    ("2022/04/Coca-Cola-Logo.png", "Coca-Cola"),
    ("2022/04/Colas_logo.png", "Colas"),
    ("2022/04/Afriquialogo.png", "Afriquia"),
    ("2022/04/winxo-logo.png", "Winxo"),
    ("2022/04/petromin-logo.png", "Petromin Oils"),
    ("2022/04/logo-movenpick.png", "Mövenpick Hotels & Resorts"),
    ("2022/04/sofitel-logo.png", "Sofitel"),
    ("2022/04/barcelologo.png", "Barceló Hotel Group"),
    ("2022/04/mazagan-logo.jpg", "Mazagan"),
    ("2022/04/hayattlogo.jpg", "Hyatt"),
    ("2022/04/la_grillardlogo.jpg", "La Grillardière"),
    ("2022/04/les-maitres-de-pain-logo.png", "Les Maîtres de Pain"),
    ("2022/04/PomDePain_Logo.png", "Pomme de Pain"),
    ("2022/04/logo_venezia_ice1.png", "Venezia Ice"),
    ("2022/04/logo_somfy_2.png", "Somfy"),
]

# Home hero carousel — genuine Alam Stores product photography, re-encoded to
# 1920x1080 WebP by tools/build_hero.py. Images only; the site has no video.
HERO_IMAGES = [
    ("hero/hero-pergola-piscine.webp", "Pergola bioclimatique installée en bord de piscine"),
    ("hero/hero-store-banne-terrasse.webp", "Store banne déployé au-dessus d'une terrasse"),
    ("hero/hero-venitiens-bois-bureau.webp", "Stores vénitiens en bois dans une salle de réunion"),
    ("hero/hero-stores-interieurs-salon.webp", "Stores intérieurs dans un salon"),
    ("hero/hero-stores-exterieurs-facade.webp", "Stores extérieurs sur une façade"),
]

# Trust signals on the home hero. Each is stated in the page copy.
HERO_TRUST = [
    ("check-circle", "Devis gratuit", "Étude et chiffrage sans engagement"),
    ("ruler", "Sur mesure", "Fabriqué aux dimensions relevées"),
    ("wrench", "Pose incluse", "Installation et réglage par nos équipes"),
]

WHY_US = [
    ("sun", "Protection solaire efficace",
     "Nous choisissons la toile et le facteur d'ouverture en fonction de "
     "l'orientation de la pièce, pas d'un catalogue."),
    ("ruler", "Fabrication sur mesure",
     "Les dimensions sont relevées sur place : baies coulissantes, formes "
     "particulières et grandes hauteurs comprises."),
    ("wrench", "Pose et réglage",
     "L'installation se termine par les réglages, un essai complet et la "
     "prise en main du matériel avec vous."),
    ("users", "Particuliers et professionnels",
     "Un salon, une chambre d'hôtel ou un open space n'appellent pas les "
     "mêmes réponses. Nous adaptons chaque projet."),
]


# --------------------------------------------------------------------------
# Helpers
# --------------------------------------------------------------------------

def esc(s):
    return html.escape(s, quote=True)


def nav_label(slug):
    return NAV_LABELS.get(slug, TITLES[slug])


def href(slug):
    return "index.html" if slug == "home" else slug + ".html"


def children(slug):
    return [s for s, _, p in PAGES if p == slug]


def ancestors(slug):
    trail, cur = [], slug
    while cur:
        trail.append(cur)
        cur = PARENTS.get(cur)
    trail.reverse()
    if trail and trail[0] != "home":
        trail.insert(0, "home")
    return trail


def top_ancestor(slug):
    trail = ancestors(slug)
    return trail[1] if len(trail) > 1 else slug


def card_blurb(slug, limit=104):
    """First sentence of the page's lead, trimmed for a card."""
    lead = (CONTENT.get(slug) or {}).get("lead", "")
    if not lead:
        return ""
    first = re.split(r"(?<=[.!?])\s+", lead.strip())[0]
    if len(first) > limit:
        first = first[:limit].rsplit(" ", 1)[0].rstrip(",;:") + "…"
    return first


def wa_link(text=None):
    base = "https://wa.me/" + CONTACT["whatsapp"]
    if text:
        import urllib.parse
        return base + "?text=" + urllib.parse.quote(text)
    return base


# --------------------------------------------------------------------------
# Sitemap
# --------------------------------------------------------------------------

def load_sitemap():
    if not os.path.exists(SITEMAP):
        sys.exit("page-sitemap.xml not found under tools/data/")
    xml = open(SITEMAP, encoding="utf-8").read()
    out = {}
    for block in re.findall(r"<url>(.*?)</url>", xml, re.S):
        loc = re.search(r"<loc>(.*?)</loc>", block).group(1)
        slug = re.sub(r"^https?://alamstores\.ma/", "", loc).strip("/") or "home"
        imgs = []
        for i in re.findall(r"<image:loc>(.*?)</image:loc>", block):
            m = re.search(r"/wp-content/uploads/(.+)$", i)
            if not m:
                continue
            rel = IMAGE_FALLBACKS.get(m.group(1), m.group(1))
            if os.path.exists(os.path.join(ROOT, "assets", "images", rel)) and rel not in imgs:
                imgs.append(rel)
        out[slug] = imgs
    return out


def thumb_for(slug, sitemap):
    imgs = sitemap.get(slug, [])
    if imgs:
        return imgs[0]
    for k in children(slug):
        found = thumb_for(k, sitemap)
        if found:
            return found
    return None


def load_products():
    """Product catalogue. Two sources, same shape:

    * `tools/data/products.json`, edited by hand, or
    * the export of the database from `api/admin/export.php` (version 2, which
      adds `variants` — the sub-products created in the back-office).

    Whatever is in the file is baked into the HTML at build time. The pages
    also refresh themselves from `api/catalog.php` at runtime, so the static
    copy is the fallback: it keeps the catalogue visible for search engines and
    for the day PHP is unavailable.
    """
    if not os.path.exists(PRODUCTS_JSON):
        return {}
    try:
        data = json.load(open(PRODUCTS_JSON, encoding="utf-8"))
    except (ValueError, OSError) as e:
        print("  ! products.json ignored (%s)" % e)
        return {}
    grouped = {}
    for prod in data.get("products", []):
        cat = prod.get("category")
        if not cat or not prod.get("title"):
            continue
        grouped.setdefault(cat, []).append(prod)
    return grouped


def media_url(path):
    """A media path is either site-relative (assets/…) or under assets/images/.

    Uploads from the back-office land in `assets/uploads/`; the images
    recovered from the old WordPress library are addressed by their original
    `2022/04/name.jpg` path.
    """
    path = (path or "").strip().lstrip("/")
    if not path:
        return None
    if path.startswith("assets/"):
        return path if os.path.exists(os.path.join(ROOT, path)) else None
    return ("assets/images/" + path
            if os.path.exists(os.path.join(ROOT, "assets", "images", path)) else None)


def youtube_id(url):
    """The 11-character id of a YouTube URL, or None.

    Only the id travels into the page: the player URL is rebuilt from it, so
    nothing from the catalogue can inject parameters into the iframe.
    """
    url = (url or "").strip()
    if not url:
        return None
    for pattern in (r"youtube\.com/watch\?(?:.*&)?v=([A-Za-z0-9_-]{11})",
                    r"youtu\.be/([A-Za-z0-9_-]{11})",
                    r"youtube(?:-nocookie)?\.com/embed/([A-Za-z0-9_-]{11})",
                    r"youtube\.com/shorts/([A-Za-z0-9_-]{11})"):
        m = re.search(pattern, url, re.I)
        if m:
            return m.group(1)
    return url if re.match(r"^[A-Za-z0-9_-]{11}$", url) else None


def product_card(prod, variant=False):
    """One catalogue entry. Mirrors the markup main.js builds from the API —
    a page must look identical whether it was served static or refreshed."""
    photo = next((u for u in (media_url(x) for x in prod.get("photos", [])) if u), None)
    vid = youtube_id(prod.get("video"))

    media = ""
    if photo:
        play = ('<button class="product__play" type="button" data-video="%s"'
                ' aria-label="Voir la vidéo : %s">%s</button>'
                % (esc(vid), esc(prod["title"]), icon("play"))) if vid else ""
        media = ('<div class="product__media">'
                 '<img src="%s" alt="%s" loading="lazy" decoding="async">%s</div>'
                 % (esc(photo), esc(prod["title"]), play))

    specs = ""
    rows = [sp for sp in prod.get("specs", []) if sp.get("label") or sp.get("value")]
    if rows:
        specs = ('<dl class="product__specs">%s</dl>' % "".join(
            "<div><dt>%s</dt><dd>%s</dd></div>"
            % (esc(sp.get("label", "")), esc(sp.get("value", ""))) for sp in rows))

    actions = []
    sheet = prod.get("datasheet") or {}
    doc = sheet.get("file")
    if doc and os.path.exists(os.path.join(ROOT, doc.lstrip("/"))):
        actions.append('<a class="btn btn--ghost btn--sm" href="%s" download>%s<span>%s</span></a>'
                       % (esc(doc.lstrip("/")), icon("download"),
                          esc(sheet.get("label") or "Fiche technique (PDF)")))
    if vid and not photo:
        actions.append('<button class="btn btn--ghost btn--sm" type="button" data-video="%s">'
                       '%s<span>Voir la vidéo</span></button>' % (esc(vid), icon("play")))
    if not variant:
        actions.append('<a class="btn btn--primary btn--sm" href="devis.html">'
                       '%s<span>Demander un devis</span></a>' % icon("sparkle"))

    variants = ""
    subs = [v for v in prod.get("variants", []) if v.get("title")]
    if subs:
        variants = ('<div class="product__variants"><h4>Déclinaisons</h4><ul>%s</ul></div>'
                    % "".join("<li>%s</li>" % product_card(v, variant=True) for v in subs))

    return ('<article class="product{sub}">{media}<div class="product__body">'
            '<h3>{title}</h3>{desc}{specs}{variants}'
            '{actions}</div></article>').format(
                sub=" product--sub" if variant else "",
                media=media, title=esc(prod["title"]),
                desc=('<p>%s</p>' % esc(prod["description"])) if prod.get("description") else "",
                specs=specs, variants=variants,
                actions=('<div class="product__actions">%s</div>' % "".join(actions))
                        if actions else "")


def products_section(slug, products):
    """The catalogue block of a category page.

    Always rendered on a category page, even when the static file holds nothing
    for it: `data-catalog` is the mount point main.js fills from the database,
    so a product added in the back-office appears here without a rebuild.
    """
    if slug in NON_CATALOGUE:
        return ""

    items = products.get(slug) or []
    cards = "\n        ".join("<li>%s</li>" % product_card(p) for p in items)

    return """  <section class="section reveal" id="produits" data-catalog="{slug}"{hidden}>
    <div class="shell">
      <div class="section-head">
        <span class="eyebrow">Catalogue</span>
        <h2>Nos modèles</h2>
      </div>
      <ul class="product-grid" data-catalog-list>
        {cards}
      </ul>
    </div>
  </section>
""".format(slug=esc(slug), cards=cards, hidden="" if items else " hidden")


# --------------------------------------------------------------------------
# Chrome
# --------------------------------------------------------------------------

def topbar_html():
    return """  <div class="topbar">
    <div class="shell topbar__inner">
      <ul class="topbar__list">
        <li class="is-optional">{pin}<span>{address_short}</span></li>
        <li>{phone}<a href="tel:{tel}">{phone_display}</a></li>
        <li class="is-wide">{clock}<span>{hours}</span></li>
      </ul>
    </div>
  </div>
""".format(pin=icon("pin"), phone=icon("phone"), clock=icon("clock"),
           address_short=esc(CONTACT["address_short"]), tel=esc(CONTACT["phone_tel"]),
           phone_display=esc(CONTACT["phone_display"]), hours=esc(CONTACT["hours"]))


def nav_tree_html():
    """Desktop mega-less dropdown navigation."""
    out = []
    for slug in NAV_TOP:
        kids = children(slug)
        if not kids:
            out.append('<li class="nav__item"><a class="nav__link" href="%s">%s</a></li>'
                       % (href(slug), esc(nav_label(slug))))
            continue
        subs = []
        for k in kids:
            gk = children(k)
            if gk:
                inner = "".join('<li><a href="%s">%s</a></li>' % (href(g), esc(nav_label(g)))
                                for g in gk)
                subs.append('<li class="nav__item"><a href="%s">%s %s</a>'
                            '<ul class="nav__sub">%s</ul></li>'
                            % (href(k), esc(nav_label(k)), icon("chevron-right", "nav__caret"), inner))
            else:
                subs.append('<li><a href="%s">%s</a></li>' % (href(k), esc(nav_label(k))))
        out.append('<li class="nav__item"><a class="nav__link" href="%s">%s %s</a>'
                   '<ul class="nav__sub">%s</ul></li>'
                   % (href(slug), esc(nav_label(slug)), icon("chevron-down", "nav__caret"), "".join(subs)))
    return "\n            ".join(out)


def drawer_tree_html():
    out = []
    for slug in NAV_TOP:
        kids = children(slug)
        if not kids:
            out.append('<li><div class="drawer__row"><a href="%s">%s</a></div></li>'
                       % (href(slug), esc(nav_label(slug))))
            continue
        subs = []
        for k in kids:
            gk = children(k)
            subs.append('<li><a href="%s">%s</a></li>' % (href(k), esc(nav_label(k))))
            for g in gk:
                subs.append('<li><a href="%s">&nbsp;&nbsp;%s</a></li>' % (href(g), esc(nav_label(g))))
        out.append(
            '<li>'
            '<div class="drawer__row"><a href="{h}">{t}</a>'
            '<button class="drawer__expand" type="button" aria-expanded="false"'
            ' aria-label="Afficher les pages {t}">{chev}</button></div>'
            '<ul class="drawer__sub"><div>{subs}</div></ul>'
            '</li>'.format(h=href(slug), t=esc(nav_label(slug)),
                           chev=icon("chevron-down"), subs="".join(subs)))
    return "\n            ".join(out)


def header_html():
    return """  <a class="skip-link" href="#main">Aller au contenu</a>
{topbar}
  <header class="site-header" id="site-header">
    <div class="shell site-header__inner">
      <a class="brand" href="index.html" aria-label="{name}, accueil">
        <img src="{logo}" alt="{name} &ndash; {tag}" width="594" height="260">
      </a>

      <nav class="nav" aria-label="Navigation principale">
        <ul class="nav__list">
            {nav}
        </ul>
      </nav>

      <a class="btn btn--primary btn--sm header__cta" href="devis.html">
        {spark}<span>Devis gratuit</span>
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
      <img src="{logo}" alt="{name}" width="594" height="260">
      <button class="drawer__close" type="button" id="drawer-close" aria-label="Fermer le menu">{close}</button>
    </div>
    <div class="drawer__body">
      <ul class="drawer__list">
            {drawer}
      </ul>
    </div>
    <div class="drawer__foot">
      <div class="drawer__contact">
        <a href="tel:{tel}">{phone_display}</a>
        <a href="mailto:{email}">{email}</a>
      </div>
      <a class="btn btn--primary btn--block" href="devis.html">{spark}<span>Devis gratuit</span></a>
    </div>
  </aside>
""".format(topbar=topbar_html(), logo=LOGO, name=esc(SITE_NAME), tag=esc(SITE_TAGLINE),
           nav=nav_tree_html(), drawer=drawer_tree_html(), close=icon("close"),
           spark=icon("sparkle"),
           tel=esc(CONTACT["phone_tel"]), phone_display=esc(CONTACT["phone_display"]),
           email=esc(CONTACT["email"]))


def crumbs_html(slug):
    trail = ancestors(slug)
    items = []
    for i, s in enumerate(trail):
        if i == len(trail) - 1:
            items.append('<li aria-current="page">%s</li>' % esc(TITLES[s]))
        else:
            items.append('<li><a href="%s">%s</a></li>' % (href(s), esc(TITLES[s])))
            items.append('<li class="sep" aria-hidden="true">%s</li>' % icon("chevron-right"))
    return "".join(items)


def page_hero_html(slug):
    data = CONTENT.get(slug) or {}
    lead = data.get("lead", "")
    return """  <section class="page-hero">
    <div class="shell page-hero__inner">
      <ol class="crumbs">{crumbs}</ol>
      <h1>{title}</h1>
      <p>{lead}</p>
    </div>
  </section>
""".format(crumbs=crumbs_html(slug), title=esc(TITLES[slug]), lead=esc(lead))


def hero_html(_images=None):
    """Pure image carousel. High-resolution product photography, no video."""
    slides = []
    for n, (rel, alt) in enumerate(HERO_IMAGES):
        slides.append(
            '<div class="hero__slide{active}" aria-hidden="{hidden}">'
            '<img src="assets/images/{rel}" alt="{alt}" width="1920" height="1080" {loading}>'
            "</div>".format(
                rel=rel, alt=esc(alt),
                active=" is-active" if n == 0 else "",
                hidden="false" if n == 0 else "true",
                loading=('fetchpriority="high" decoding="async"' if n == 0
                         else 'loading="lazy" decoding="async"')))

    trust = "".join(
        '<div class="hero__trust-item">{i}<div><strong>{t}</strong><span>{s}</span></div></div>'.format(
            i=icon(ic), t=esc(t), s=esc(s)) for ic, t, s in HERO_TRUST)

    return """  <section class="hero" data-hero aria-roledescription="carrousel"
           aria-label="Réalisations Alam Stores">
    <div class="hero__media">
      {slides}
    </div>
    <div class="shell hero__inner">
      <div class="hero__body">
        <div class="badge-row">
          <span class="badge badge--glass">{check} Devis gratuit</span>
          <span class="badge badge--glass">{truck} Installation rapide</span>
          <span class="badge badge--glass">{ruler} Fabrication sur mesure</span>
        </div>
        <h1>Stores, pergolas et <span class="accent">protection solaire</span> sur mesure</h1>
        <p class="hero__lede">{tagline}. Nous concevons, fabriquons et posons vos stores
           intérieurs et extérieurs, pergolas, moustiquaires et motorisations —
           étudiés pour votre exposition et l'usage réel de chaque pièce.</p>
        <div class="btn-row">
          <a class="btn btn--primary btn--lg" href="devis.html">{spark}<span>Demander un devis</span></a>
        </div>
        <div class="hero__trust">{trust}</div>
      </div>
    </div>
    <div class="hero__dots" data-hero-dots></div>
  </section>
""".format(slides="\n      ".join(slides), tagline=esc(SITE_TAGLINE), trust=trust,
           check=icon("check"), truck=icon("truck"), ruler=icon("ruler"),
           spark=icon("sparkle"))


# --------------------------------------------------------------------------
# Sections
# --------------------------------------------------------------------------

def cards_html(slugs, sitemap, tag=None):
    items = []
    for s in slugs:
        thumb = thumb_for(s, sitemap)
        media = ('<div class="card__media">'
                 '<img src="assets/images/%s" alt="%s" loading="lazy" decoding="async">'
                 '%s</div>'
                 % (thumb, esc(TITLES[s]),
                    '<span class="card__tag">%s</span>' % esc(tag) if tag else "")) if thumb else ""
        blurb = card_blurb(s)
        items.append(
            '<li><article class="card">'
            '{media}'
            '<div class="card__body">'
            '<h3 class="card__title">{title}</h3>'
            '{blurb}'
            '<div class="card__foot">'
            '<span class="card__more">Découvrir {arrow}</span>'
            '</div></div>'
            '<a class="card__link" href="{h}"><span class="sr-only">{title}</span></a>'
            '</article></li>'.format(
                media=media, title=esc(TITLES[s]),
                blurb='<p class="card__text">%s</p>' % esc(blurb) if blurb else "",
                arrow=icon("arrow-right"), h=href(s)))
    return '<ul class="card-grid">\n        %s\n      </ul>' % "\n        ".join(items)


def category_section(slug, sitemap, eyebrow=None, title=None, alt=False, blurb=True):
    """`blurb=False` when the page hero already carries this page's lead."""
    kids = children(slug)
    if not kids:
        return ""
    lede = ('<p class="lede">%s</p>' % esc(card_blurb(slug, 190))) if blurb else ""
    return """  <section class="section{alt} reveal">
    <div class="shell">
      <div class="section-head">
        <span class="eyebrow">{eyebrow}</span>
        <h2>{title}</h2>
        {lede}
      </div>
      {cards}
    </div>
  </section>
""".format(alt=" section--alt" if alt else "",
           eyebrow=esc(eyebrow or "Notre gamme"),
           title=esc(title or TITLES[slug]),
           lede=lede,
           cards=cards_html(kids, sitemap, CARD_TAGS.get(top_ancestor(slug))))


def prose_html(slug, skip_lead=False):
    data = CONTENT.get(slug)
    if not data:
        return ""
    out = []
    if data.get("lead") and not skip_lead:
        out.append('        <p class="lede">%s</p>' % esc(data["lead"]))
    for sec in data.get("sections", []):
        out.append("        <h2>%s</h2>" % esc(sec["h"]))
        for para in sec.get("p", []):
            out.append("        <p>%s</p>" % esc(para))
        if sec.get("ul"):
            out.append("        <ul>")
            for item in sec["ul"]:
                out.append("          <li>%s</li>" % esc(item))
            out.append("        </ul>")
    return ('      <div class="prose" data-content-slot="%s">\n%s\n      </div>\n'
            % (slug, "\n".join(out)))


def contact_card_html(compact=False):
    rows = [
        ("pin", "Adresse", esc(CONTACT["address"]), None),
        ("phone", "Téléphone", esc(CONTACT["phone_display"]), "tel:" + esc(CONTACT["phone_tel"])),
        ("whatsapp", "WhatsApp", esc(CONTACT["whatsapp_display"]), esc(wa_link())),
        ("mail", "E-mail", esc(CONTACT["email"]), "mailto:" + esc(CONTACT["email"])),
        ("clock", "Horaires",
         esc(CONTACT["hours_week"]) + "<br>" + esc(CONTACT["hours_sat"]), None),
    ]
    lis = []
    for ic, label, value, link in rows:
        inner = ('<a href="%s"%s>%s</a>' % (link, ' target="_blank" rel="noopener"'
                                            if link.startswith("http") else "", value)) if link else \
                ("<span>%s</span>" % value)
        lis.append("<li>%s<div><strong>%s</strong>%s</div></li>" % (icon(ic), label, inner))
    body = '<ul class="contact-list">%s</ul>' % "".join(lis)
    if compact:
        return body
    return ('<aside class="contact-card sticky-aside">'
            '<h2>Nous joindre directement</h2>%s'
            '<a class="btn btn--primary btn--block" style="margin-top:20px" href="devis.html">'
            '%s<span>Demander un devis</span></a>'
            "</aside>" % (body, icon("sparkle")))


def prose_section(slug, with_aside=True, skip_lead=False, center=False):
    copy = prose_html(slug, skip_lead=skip_lead)
    if not copy:
        return ""
    if not with_aside:
        cls = "prose-wrap is-center" if center else "prose-wrap"
        return ('  <section class="section">\n    <div class="shell %s">\n%s'
                "    </div>\n  </section>\n" % (cls, copy))
    return """  <section class="section">
    <div class="shell split">
      <div>
{copy}      </div>
      <div>{aside}</div>
    </div>
  </section>
""".format(copy=copy, aside=contact_card_html())


def _aspect(rel):
    """Aspect ratio of an image, or None when it cannot be read."""
    path = os.path.join(ROOT, "assets", "images", rel)
    try:
        from PIL import Image
        with Image.open(path) as im:
            w, h = im.size
        return (w / h) if h else None
    except Exception:
        return None


# Photos outside this band (logos, banners, portraits) are letterboxed instead
# of being cropped to the grid's 4:3 tile.
CROP_SAFE = (1.15, 1.85)


def gallery_html(slug, images):
    if not images:
        return ""
    items = []
    for n, rel in enumerate(images, 1):
        cap = "%s — photo %d" % (TITLES[slug], n)
        ar = _aspect(rel)
        contain = ar is not None and not (CROP_SAFE[0] <= ar <= CROP_SAFE[1])
        items.append(
            '<li class="gallery__item{mod}">'
            '<a href="assets/images/{rel}" data-lightbox="{slug}" data-caption="{cap}">'
            '<img src="assets/images/{rel}" alt="{cap}" loading="lazy" decoding="async">'
            '<span class="gallery__zoom">{zoom}</span></a></li>'.format(
                rel=rel, slug=slug, cap=esc(cap), zoom=icon("zoom"),
                mod=" gallery__item--contain" if contain else ""))
    return """  <section class="section section--alt reveal" id="galerie">
    <div class="shell">
      <div class="section-head">
        <span class="eyebrow">Galerie</span>
        <h2>Nos réalisations</h2>
      </div>
      <ul class="gallery">
        {items}
      </ul>
    </div>
  </section>
""".format(items="\n        ".join(items))


def partners_html(alt=False):
    """Infinite, continuously scrolling logo marquee.

    The track is rendered twice; the CSS animation translates by exactly -50%,
    so the second copy lands where the first began and the loop is seamless.
    """
    logos = [(rel, name) for rel, name in PARTNER_LOGOS
             if os.path.exists(os.path.join(ROOT, "assets", "images", rel))]
    if not logos:
        return ""

    def run(hidden):
        return "".join(
            '<li%s><div><img src="assets/images/%s" alt="%s" loading="lazy" decoding="async"></div></li>'
            % (' aria-hidden="true"' if hidden else "", rel, esc(name))
            for rel, name in logos)

    return """  <section class="section{alt} reveal">
    <div class="shell">
      <div class="section-head">
        <span class="eyebrow">Références</span>
        <h2>Ils nous ont fait confiance</h2>
        <p class="lede">Hôtels, groupes industriels, restaurants et enseignes commerciales
           équipés en stores et aménagements sur mesure.</p>
      </div>
    </div>
    <div class="marquee" data-marquee>
      <ul class="marquee__track" style="--marquee-count:{count}">
        {a}{b}
      </ul>
    </div>
  </section>
""".format(alt=" section--alt" if alt else "", count=len(logos), a=run(False), b=run(True))


def why_html():
    items = "".join(
        '<li class="feature"><span class="feature__icon">{i}</span>'
        '<h3>{t}</h3><p>{d}</p></li>'.format(i=icon(ic), t=esc(t), d=esc(d))
        for ic, t, d in WHY_US)
    return """  <section class="section section--alt reveal">
    <div class="shell">
      <div class="section-head is-center">
        <span class="eyebrow">Pourquoi Alam Stores</span>
        <h2>Du conseil à la pose, un seul interlocuteur</h2>
      </div>
      <ul class="feature-grid">
        {items}
      </ul>
    </div>
  </section>
""".format(items=items)


def cta_band():
    return """  <section class="section section--tight reveal">
    <div class="shell">
      <div class="cta-band">
        <div class="cta-band__inner">
          <div>
            <h2>Un projet de stores ou de pergola&nbsp;?</h2>
            <p>Décrivez votre besoin : nous étudions l'exposition, relevons les mesures
               sur place et vous remettons un devis gratuit et sans engagement.</p>
          </div>
          <div class="btn-row">
            <a class="btn btn--primary btn--lg" href="devis.html">{spark}<span>Demander un devis</span></a>
          </div>
        </div>
      </div>
    </div>
  </section>
""".format(spark=icon("sparkle"))


# --------------------------------------------------------------------------
# Quote form
# --------------------------------------------------------------------------

# Professional status. The value marked B2B reveals the company-name field.
STATUTS = [
    ("Particulier", False),
    ("Entreprise / Professionnel (B2B)", True),
    ("Architecte / Revendeur", True),
]

# Categories offered in the quote form. Wider than the page tree on purpose —
# "Rideaux" and "Autre" are sold but have no dedicated page.
FORM_CATEGORIES = [
    "Stores Extérieurs", "Stores Intérieurs", "Pergolas", "Moustiquaires",
    "Rideaux", "Stores Enrouleurs", "Stores Vénitiens", "Stores Californiens",
    "Stores Bateaux", "Panneaux Japonais", "Toiles Tendues", "Parasols",
    "Abris de Voiture", "Motorisations & Automatismes", "Autre / Projet mixte",
]


def showroom_card():
    """Address, phone and opening hours, shown above the quote form."""
    return """  <section class="section section--tight">
    <div class="shell">
      <div class="showroom">
        <div class="showroom__item">
          <span class="showroom__icon">{pin}</span>
          <div>
            <strong>Showroom</strong>
            <p>{address}</p>
          </div>
        </div>
        <div class="showroom__item">
          <span class="showroom__icon">{phone}</span>
          <div>
            <strong>Téléphone</strong>
            <p><a href="tel:{tel}">{phone_display}</a><br>
               <a href="{wa}" target="_blank" rel="noopener">WhatsApp&nbsp;: {wa_display}</a></p>
          </div>
        </div>
        <div class="showroom__item">
          <span class="showroom__icon">{clock}</span>
          <div>
            <strong>Horaires d'ouverture</strong>
            <p>{hours_week}<br>{hours_sat}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
""".format(pin=icon("pin"), phone=icon("phone"), clock=icon("clock"),
           address=esc(CONTACT["address"]),
           tel=esc(CONTACT["phone_tel"]),
           phone_display=esc(CONTACT["phone_display"]),
           wa_display=esc(CONTACT["whatsapp_display"]),
           wa=esc(wa_link()),
           hours_week=esc(CONTACT["hours_week"]),
           hours_sat=esc(CONTACT["hours_sat"]))


def quote_form():
    statuts = "".join(
        '<option value="%s"%s>%s</option>'
        % (esc(label), ' data-b2b="1"' if is_b2b else "", esc(label))
        for label, is_b2b in STATUTS)
    cats = "".join('<option value="%s">%s</option>' % (esc(c), esc(c))
                   for c in FORM_CATEGORIES)

    return """  <section class="section" style="padding-top:clamp(24px,3vw,36px)">
    <div class="shell">
      <form class="form-card" id="devis-form" data-quote-form novalidate
            data-whatsapp="{whatsapp}">
        <div class="form-card__head">
          <h2>Demande de devis</h2>
          <p>Particuliers, entreprises, architectes et revendeurs : décrivez votre
             projet et nous revenons vers vous avec une proposition chiffrée.
             Votre demande est mise en forme dans WhatsApp&nbsp;: vous la relisez
             et vous l'envoyez d'un seul geste.</p>
        </div>

        <div class="form-card__body">
          <fieldset class="fieldset">
            <legend class="fieldset__title">Votre projet</legend>
            <p class="fieldset__hint">Ces trois champs nous suffisent pour vous orienter.</p>
            <div class="field-grid">
              <div class="field">
                <label for="f-statut">Statut professionnel <span class="req" aria-hidden="true">*</span></label>
                <select id="f-statut" name="statut" required data-statut>{statuts}</select>
                <span class="field__error" data-error-for="statut"></span>
              </div>
              <div class="field">
                <label for="f-category">Catégorie <span class="req" aria-hidden="true">*</span></label>
                <select id="f-category" name="category" required>{cats}</select>
                <span class="field__error" data-error-for="category"></span>
              </div>
              <div class="field field--full" data-b2b-field hidden>
                <label for="f-company">Nom de l'entreprise <span class="req" aria-hidden="true">*</span></label>
                <input id="f-company" name="company" type="text" autocomplete="organization"
                       placeholder="Raison sociale, cabinet ou enseigne">
                <span class="field__error" data-error-for="company"></span>
              </div>
              <div class="field field--full">
                <label for="f-message">Message <span class="req" aria-hidden="true">*</span></label>
                <textarea id="f-message" name="message" rows="5" required
                  placeholder="Nombre d'ouvertures, dimensions approximatives, pièce concernée, orientation, commande manuelle ou motorisée, délai souhaité…"></textarea>
                <span class="field__error" data-error-for="message"></span>
              </div>
            </div>
          </fieldset>

          <fieldset class="fieldset">
            <legend class="fieldset__title">Vos coordonnées</legend>
            <p class="fieldset__hint">Pour vous recontacter et, si besoin, planifier la prise de mesures.</p>
            <div class="field-grid">
              <div class="field">
                <label for="f-firstname">Prénom <span class="req" aria-hidden="true">*</span></label>
                <input id="f-firstname" name="firstname" type="text" required autocomplete="given-name">
                <span class="field__error" data-error-for="firstname"></span>
              </div>
              <div class="field">
                <label for="f-lastname">Nom <span class="req" aria-hidden="true">*</span></label>
                <input id="f-lastname" name="lastname" type="text" required autocomplete="family-name">
                <span class="field__error" data-error-for="lastname"></span>
              </div>
              <div class="field">
                <label for="f-email">E-mail <span class="req" aria-hidden="true">*</span></label>
                <input id="f-email" name="email" type="email" required autocomplete="email">
                <span class="field__error" data-error-for="email"></span>
              </div>
              <div class="field">
                <label for="f-phone">Téléphone <span class="req" aria-hidden="true">*</span></label>
                <input id="f-phone" name="phone" type="tel" required autocomplete="tel">
                <span class="field__error" data-error-for="phone"></span>
              </div>
              <div class="field">
                <label for="f-country">Pays</label>
                <input id="f-country" name="country" type="text" value="Maroc" autocomplete="country-name">
              </div>
              <div class="field">
                <label for="f-city">Ville</label>
                <input id="f-city" name="city" type="text" autocomplete="address-level2">
              </div>
              <div class="field">
                <label for="f-address">Adresse</label>
                <input id="f-address" name="address" type="text" autocomplete="street-address">
              </div>
              <div class="field">
                <label for="f-zip">Code postal</label>
                <input id="f-zip" name="zip" type="text" autocomplete="postal-code" inputmode="numeric">
              </div>
            </div>
          </fieldset>

          <div class="form-nav">
            <button class="btn btn--primary btn--lg" type="submit">
              {wapp}<span>Envoyer ma demande</span></button>
          </div>
          <p class="form-status" role="status" aria-live="polite"></p>
          <p class="form-note">Un r&eacute;capitulatif complet s'ouvre dans WhatsApp, pr&ecirc;t
             &agrave; envoyer au {phone}&nbsp;: vous relisez et vous gardez la main sur l'envoi.
             Pas de WhatsApp&nbsp;? &Eacute;crivez-nous &agrave;
             <a href="mailto:{email}">{email}</a>.</p>
        </div>
      </form>
    </div>
  </section>
""".format(whatsapp=esc(CONTACT["whatsapp"]), email=esc(CONTACT["email"]),
           phone=esc(CONTACT["whatsapp_display"]), statuts=statuts, cats=cats,
           wapp=icon("whatsapp"))


# --------------------------------------------------------------------------
# Footer
# --------------------------------------------------------------------------

def footer_html():
    def col(title, slugs):
        lis = "".join('<li><a href="%s">%s</a></li>' % (href(s), esc(TITLES[s])) for s in slugs)
        return "<div><h3>%s</h3><ul>%s</ul></div>" % (esc(title), lis)

    return """  <footer class="site-footer">
    <div class="shell">
      <div class="site-footer__grid">
        <div class="site-footer__brand">
          <img src="{logo}" alt="{name}" width="594" height="260">
          <p>{tag}. Stores intérieurs et extérieurs, pergolas, moustiquaires et
             motorisations sur mesure.</p>
          <div class="footer-social">
            <a href="{linkedin}" target="_blank" rel="noopener"
               aria-label="Alam Stores sur LinkedIn">{li}</a>
            <a href="mailto:{email}" aria-label="E-mail">{mail}</a>
            <a href="tel:{tel}" aria-label="Téléphone">{phone}</a>
          </div>
        </div>
        {c1}
        {c2}
        <div>
          <h3>Contact</h3>
          {contact}
        </div>
      </div>
      <div class="site-footer__bottom">
        <span>&copy; <span id="year">2026</span> {name}. Tous droits réservés.</span>
        <nav aria-label="Liens de pied de page">
          <a href="societe.html">Société</a>
          <a href="service.html">Service</a>
          <a href="partenaires.html">Partenaires</a>
          <a href="devis.html">Devis</a>
        </nav>
      </div>
    </div>
  </footer>

  <div class="fab">
    <a class="fab__wa" href="https://wa.me/{whatsapp}" target="_blank" rel="noopener"
       aria-label="Nous écrire sur WhatsApp"><span class="fab__pulse" aria-hidden="true"></span>{wapp}</a>
    <button class="to-top" type="button" id="to-top" aria-label="Retour en haut de page">{up}</button>
  </div>
""".format(logo=LOGO, name=esc(SITE_NAME), tag=esc(SITE_TAGLINE),
           mail=icon("mail"), phone=icon("phone"), up=icon("arrow-up"),
           wapp=icon("whatsapp"), whatsapp=esc(CONTACT["whatsapp"]),
           li=icon("linkedin"), linkedin=esc(CONTACT["linkedin"]),
           email=esc(CONTACT["email"]), tel=esc(CONTACT["phone_tel"]),
           c1=col("Stores Intérieurs", ["stores-enrouleurs", "stores-venitiens",
                                        "stores-californiens", "stores-bateaux",
                                        "store-duo-jour-nuit", "panneaux-japonais"]),
           c2=col("Stores Extérieurs", ["pergolas", "parasols", "toiles-tendues",
                                        "abris-de-voiture", "moustiquaires"]),
           contact=contact_card_html(compact=True))


# --------------------------------------------------------------------------
# Page assembly
# --------------------------------------------------------------------------

PAGE = """<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{title}</title>
<meta name="description" content="{description}">
<link rel="canonical" href="{canonical}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{site}">
<meta property="og:locale" content="fr_FR">
<meta property="og:title" content="{title}">
<meta property="og:description" content="{description}">
<meta property="og:url" content="{canonical}">
<meta property="og:image" content="{og_image}">
<meta name="twitter:card" content="summary_large_image">
<meta name="theme-color" content="#7d0e7c">
<link rel="icon" href="{logo}" type="image/png">
<link rel="apple-touch-icon" href="{logo}">
<link rel="preload" as="style" href="assets/css/main.min.css">
<link rel="stylesheet" href="assets/css/main.min.css">
{jsonld}</head>
<body class="page-{slug}">
<a id="top"></a>
{header}
<main id="main">
{content}</main>
{footer}
<script src="assets/js/main.min.js" defer></script>
</body>
</html>
"""


def meta_description(slug, title):
    data = CONTENT.get(slug) or {}
    return data.get("meta") or ("%s - %s, %s." % (title, SITE_NAME, SITE_TAGLINE))


def jsonld_for(slug):
    blocks = []

    trail = ancestors(slug)
    if len(trail) > 1:
        blocks.append({
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [{
                "@type": "ListItem",
                "position": i + 1,
                "name": TITLES[s],
                "item": "%s/%s" % (SITE_URL, "" if s == "home" else s + "/"),
            } for i, s in enumerate(trail)],
        })

    if slug == "home":
        blocks.append({
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": SITE_NAME,
            "description": SITE_TAGLINE,
            "url": SITE_URL + "/",
            "logo": "%s/%s" % (SITE_URL, LOGO),
            "image": "%s/%s" % (SITE_URL, LOGO),
            "telephone": CONTACT["phone_tel"],
            "email": CONTACT["email"],
            "address": {"@type": "PostalAddress",
                        "addressLocality": CONTACT["address"],
                        "addressCountry": "MA"},
            "areaServed": "MA",
        })

    return "".join('<script type="application/ld+json">%s</script>\n'
                   % json.dumps(b, ensure_ascii=False, separators=(",", ":")) for b in blocks)


def build():
    sitemap = load_sitemap()
    products = load_products()
    header = header_html()
    footer = footer_html()
    written = []

    for slug, title, parent in PAGES:
        images = sitemap.get(slug, [])
        body = []

        if slug == "home":
            body.append(hero_html(images))
            body.append(prose_section("home", with_aside=False, center=True))
            body.append(category_section("stores-interieurs", sitemap,
                                         "Intérieur", "Stores Intérieurs", alt=True))
            body.append(category_section("stores-exterieurs", sitemap,
                                         "Extérieur", "Stores Extérieurs"))
            body.append(why_html())
            body.append(partners_html())
            body.append(cta_band())
        elif slug == "devis":
            body.append(page_hero_html(slug))
            body.append(showroom_card())
            body.append(quote_form())
            body.append(prose_section(slug, with_aside=False, skip_lead=True, center=True))
        elif slug == "partenaires":
            body.append(page_hero_html(slug))
            body.append(prose_section(slug, skip_lead=True))
            body.append(partners_html(alt=True))
            body.append(cta_band())
        else:
            body.append(page_hero_html(slug))
            body.append(prose_section(slug, skip_lead=True))
            body.append(category_section(slug, sitemap, alt=True, blurb=False))
            body.append(products_section(slug, products))
            body.append(cta_band())

        og = images[0] if images else None
        out = PAGE.format(
            title=esc(("%s | %s" % (title, SITE_NAME)) if slug != "home"
                      else ("%s | %s" % (SITE_NAME, SITE_TAGLINE))),
            description=esc(meta_description(slug, title)),
            canonical="%s/%s" % (SITE_URL, "" if slug == "home" else slug + "/"),
            og_image="%s/assets/images/%s" % (SITE_URL, og) if og else "%s/%s" % (SITE_URL, LOGO),
            site=esc(SITE_NAME), logo=LOGO, slug=slug,
            jsonld=jsonld_for(slug), header=header,
            content="".join(x for x in body if x), footer=footer)

        with open(os.path.join(ROOT, href(slug)), "w", encoding="utf-8") as fh:
            fh.write(out)
        written.append((href(slug), len(images)))

    # 404
    not_found = PAGE.format(
        title=esc("Page introuvable | " + SITE_NAME),
        description=esc("La page demandée est introuvable."),
        canonical=SITE_URL + "/404.html",
        og_image="%s/%s" % (SITE_URL, LOGO),
        site=esc(SITE_NAME), logo=LOGO, slug="404", jsonld="", header=header,
        content="""  <section class="page-hero">
    <div class="shell page-hero__inner">
      <ol class="crumbs"><li><a href="index.html">Accueil</a></li>
        <li class="sep" aria-hidden="true">{chev}</li><li aria-current="page">Erreur 404</li></ol>
      <h1>Page introuvable</h1>
      <p>La page que vous cherchez n'existe pas ou a été déplacée.</p>
    </div>
  </section>
  <section class="section">
    <div class="shell">
      <div class="btn-row">
        <a class="btn btn--primary btn--lg" href="index.html">{arrow}<span>Retour à l'accueil</span></a>
        <a class="btn btn--ghost btn--lg" href="devis.html">Demander un devis</a>
      </div>
    </div>
  </section>
""".format(chev=icon("chevron-right"), arrow=icon("arrow-right")),
        footer=footer)
    with open(os.path.join(ROOT, "404.html"), "w", encoding="utf-8") as fh:
        fh.write(not_found)

    print("Generated %d pages + 404.html" % len(written))
    for name, n in written:
        print("  %-34s %2d image(s)" % (name, n))


if __name__ == "__main__":
    build()
