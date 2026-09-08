#!/usr/bin/env python3
"""
Generate the Alam Stores static site.

Every fact this script emits is derived from the original WordPress install:

  * page inventory and per-page images -> Rank Math sitemap cache
    (wp-content/uploads/rank-math/rank_math_*.xml)
  * markup classes and layout          -> the `constrau` theme templates
  * logo and media                     -> wp-content/uploads

Page body copy lived in the WordPress database, which was not part of the
export. Those slots are marked with a `.content-pending` notice and are
filled in by tools/fetch_content.py once the live site is reachable.

Usage:  python3 tools/build_pages.py
"""

import html
import json
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SITEMAP_CANDIDATES = [
    os.path.join(ROOT, "tools", "data", "page-sitemap.xml"),
]

SITE_NAME = "Alam Stores"
SITE_TAGLINE = "Le Spécialiste de l'Aménagement et de la Décoration"
SITE_URL = "https://alamstores.ma"
LOGO = "assets/images/2019/05/Logo-stores-rideaux-maroc.png"

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
NAV_LABELS = {
    "motorisations-automatismes": "Motorisations",
}

# Top-level navigation order.
NAV_TOP = [
    "home", "societe", "stores-interieurs", "stores-exterieurs",
    "motorisations-automatismes", "service", "partenaires",
]


def nav_label(slug):
    return NAV_LABELS.get(slug, TITLES[slug])


def href(slug):
    return "index.html" if slug == "home" else slug + ".html"


def children(slug):
    return [s for s, _, p in PAGES if p == slug]


def esc(s):
    return html.escape(s, quote=True)


# --------------------------------------------------------------------------
# Sitemap
# --------------------------------------------------------------------------

def load_sitemap():
    path = next((p for p in SITEMAP_CANDIDATES if os.path.exists(p)), None)
    if not path:
        sys.exit("page-sitemap.xml not found under tools/data/")
    xml = open(path, encoding="utf-8").read()
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
            if os.path.exists(os.path.join(ROOT, "assets", "images", rel)):
                if rel not in imgs:
                    imgs.append(rel)
        out[slug] = imgs
    return out


# --------------------------------------------------------------------------
# Fragments
# --------------------------------------------------------------------------

def nav_items():
    parts = []
    for slug in NAV_TOP:
        kids = children(slug)
        if not kids:
            parts.append(
                '<li class="nav-item"><a class="nav-link" href="%s">%s</a></li>'
                % (href(slug), esc(nav_label(slug)))
            )
            continue
        sub = []
        for k in kids:
            gk = children(k)
            if gk:
                inner = "".join(
                    '<li><a href="%s">%s</a></li>' % (href(g), esc(nav_label(g))) for g in gk
                )
                sub.append(
                    '<li class="dropdown menu-item-has-children">'
                    '<a href="%s">%s</a>'
                    '<button class="dropdown-toggle" type="button" aria-expanded="false"'
                    ' aria-label="Afficher le sous-menu %s"></button>'
                    '<ul class="dropdown-menu">%s</ul></li>'
                    % (href(k), esc(nav_label(k)), esc(nav_label(k)), inner)
                )
            else:
                sub.append('<li><a href="%s">%s</a></li>' % (href(k), esc(nav_label(k))))
        parts.append(
            '<li class="nav-item dropdown menu-item-has-children">'
            '<a class="nav-link" href="%s">%s</a>'
            '<button class="dropdown-toggle" type="button" aria-expanded="false"'
            ' aria-label="Afficher le sous-menu %s"></button>'
            '<ul class="dropdown-menu">%s</ul></li>'
            % (href(slug), esc(nav_label(slug)), esc(nav_label(slug)), "".join(sub))
        )
    parts.append(
        '<li class="nav-item nav-cta"><a class="nav-link" href="devis.html">Devis gratuit</a></li>'
    )
    return "\n            ".join(parts)


def header_html(depth_prefix=""):
    return """  <a class="skip-link" href="#main">Aller au contenu</a>

  <header class="ovatheme_header_default" id="ovatheme_header_default">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <nav class="navbar navbar-expand-lg px-0 py-0">
            <a href="index.html" class="navbar-brand">
              <img src="{logo}" alt="{name} &ndash; {tag}" width="594" height="260">
            </a>
            <button class="navbar-toggler" type="button" data-target="#header_menu"
                    aria-controls="header_menu" aria-expanded="false" aria-label="Afficher la navigation">
              <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="header_menu">
              <ul class="nav navbar-nav navbar-right">
            {nav}
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </header>
""".format(logo=depth_prefix + LOGO, name=esc(SITE_NAME), tag=esc(SITE_TAGLINE), nav=nav_items())


def breadcrumb_html(slug):
    trail = []
    cur = slug
    while cur:
        trail.append(cur)
        cur = PARENTS.get(cur)
    trail.reverse()
    if trail and trail[0] != "home":
        trail.insert(0, "home")

    items = []
    for i, s in enumerate(trail):
        last = i == len(trail) - 1
        if last:
            items.append('<li aria-current="page">%s</li>' % esc(TITLES[s]))
        else:
            items.append('<li><a href="%s">%s</a></li>' % (href(s), esc(TITLES[s])))
            items.append('<li class="separator" aria-hidden="true"></li>')
    return "\n            ".join(items)


def page_header_html(slug):
    if slug == "home":
        return ""
    return """  <div class="ovatheme_breadcrumbs ovatheme_breadcrumbs_default">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class="page-title">{title}</h1>
          <ul class="breadcrumb">
            {crumbs}
          </ul>
        </div>
      </div>
    </div>
  </div>
""".format(title=esc(TITLES[slug]), crumbs=breadcrumb_html(slug))


def gallery_html(slug, images):
    if not images:
        return ""
    items = []
    for n, rel in enumerate(images, 1):
        caption = "%s &ndash; photo %d" % (TITLES[slug], n)
        items.append(
            '<li><a href="assets/images/{rel}" data-lightbox="{slug}" data-caption="{cap}">'
            '<img src="assets/images/{rel}" alt="{cap}" loading="lazy" decoding="async">'
            "</a></li>".format(rel=rel, slug=slug, cap=caption)
        )
    return """  <section class="section" id="galerie">
    <div class="container">
      <h2 class="section-title">Réalisations</h2>
      <ul class="gallery-grid">
        {items}
      </ul>
    </div>
  </section>
""".format(items="\n        ".join(items))


def children_cards_html(slug, sitemap):
    kids = children(slug)
    if not kids:
        return ""
    cards = []
    for k in kids:
        imgs = sitemap.get(k, [])
        thumb = imgs[0] if imgs else None
        if not thumb:
            for g in children(k):
                gi = sitemap.get(g, [])
                if gi:
                    thumb = gi[0]
                    break
        img_tag = (
            '<img src="assets/images/%s" alt="%s" loading="lazy" decoding="async">' % (thumb, esc(TITLES[k]))
            if thumb else ""
        )
        cards.append(
            '<li><a href="{h}">{img}<span class="card-body"><span>{t}</span></span></a></li>'.format(
                h=href(k), img=img_tag, t=esc(TITLES[k])
            )
        )
    return """  <section class="section">
    <div class="container">
      <h2 class="section-title">Notre gamme</h2>
      <ul class="card-grid">
        {cards}
      </ul>
    </div>
  </section>
""".format(cards="\n        ".join(cards))


CONTENT_PENDING = """      <div class="content-pending" data-content-slot="{slug}">
        <strong>Contenu à restaurer</strong>
        <p>Le texte original de cette page se trouvait dans la base de données WordPress,
           qui ne faisait pas partie de l'export. Lancez
           <code>python3 tools/fetch_content.py</code> depuis une machine ayant accès à
           alamstores.ma pour réinjecter automatiquement le contenu d'origine.</p>
      </div>
"""


def contact_strip():
    return """  <section class="contact-strip">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h2>Un projet de stores ou de pergola&nbsp;?</h2>
          <p>Demandez une étude et un devis gratuits pour votre aménagement.</p>
        </div>
        <div class="col-md-4 text-md-right mt-3 mt-md-0">
          <a class="btn-primary-as" href="devis.html">Demander un devis</a>
        </div>
      </div>
    </div>
  </section>
"""


def footer_html():
    def col(title, slugs):
        lis = "".join(
            '<li><a href="%s">%s</a></li>' % (href(s), esc(TITLES[s])) for s in slugs
        )
        return (
            '<div class="col-md-3 col-sm-6 mb-4"><h3>%s</h3><ul>%s</ul></div>'
            % (esc(title), lis)
        )

    return """  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="footer-brand mb-3">
            <img src="{logo}" alt="{name}" width="594" height="260">
          </div>
          <p>{tag}</p>
        </div>
        {c1}
        {c2}
        {c3}
      </div>
      <div class="footer-bottom">
        <div class="row">
          <div class="col-md-6">&copy; <span id="year">2026</span> {name}. Tous droits réservés.</div>
          <div class="col-md-6 text-md-right"><a href="devis.html">Demander un devis</a></div>
        </div>
      </div>
    </div>
  </footer>
""".format(
        logo=LOGO,
        name=esc(SITE_NAME),
        tag=esc(SITE_TAGLINE),
        c1=col("Stores Intérieurs", ["stores-enrouleurs", "stores-venitiens",
                                    "stores-californiens", "stores-bateaux",
                                    "store-duo-jour-nuit", "panneaux-japonais"]),
        c2=col("Stores Extérieurs", ["pergolas", "parasols", "toiles-tendues",
                                    "abris-de-voiture", "moustiquaires"]),
        c3=col("Société", ["societe", "service", "motorisations-automatismes",
                           "partenaires", "devis"]),
    )


def hero_html(images):
    slides = []
    for n, rel in enumerate(images[:6]):
        slides.append(
            '<div class="slide" aria-hidden="{hidden}">'
            '<img src="assets/images/{rel}" alt="" {loading} decoding="async">'
            "</div>".format(rel=rel, hidden="false" if n == 0 else "true",
                            loading='fetchpriority="high"' if n == 0 else 'loading="lazy"')
        )
    return """  <section class="hero" data-slider data-interval="6000" aria-roledescription="carrousel"
           aria-label="Réalisations Alam Stores">
    {slides}
    <button class="slider-prev" type="button" aria-label="Diapositive précédente">&#10094;</button>
    <button class="slider-next" type="button" aria-label="Diapositive suivante">&#10095;</button>
    <div class="slider-dots"></div>
  </section>
""".format(slides="\n    ".join(slides))


def quote_form():
    options = "".join(
        '<option value="%s">%s</option>' % (esc(TITLES[s]), esc(TITLES[s]))
        for s in ["stores-interieurs", "stores-exterieurs", "pergolas", "parasols",
                  "moustiquaires", "toiles-tendues", "abris-de-voiture",
                  "motorisations-automatismes"]
    )
    return """  <section class="section">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <h2 class="section-title">Demande de devis</h2>
          <p class="section-lead">Décrivez votre projet et nous vous recontactons avec une proposition.</p>
          <form data-validate novalidate>
            <div class="row">
              <div class="col-md-6"><div class="form-field">
                <label for="f-name">Nom et prénom <span aria-hidden="true">*</span></label>
                <input id="f-name" name="name" type="text" required autocomplete="name">
              </div></div>
              <div class="col-md-6"><div class="form-field">
                <label for="f-email">E-mail <span aria-hidden="true">*</span></label>
                <input id="f-email" name="email" type="email" required autocomplete="email">
              </div></div>
              <div class="col-md-6"><div class="form-field">
                <label for="f-phone">Téléphone</label>
                <input id="f-phone" name="phone" type="tel" autocomplete="tel">
              </div></div>
              <div class="col-md-6"><div class="form-field">
                <label for="f-product">Produit souhaité</label>
                <select id="f-product" name="product">{options}</select>
              </div></div>
              <div class="col-12"><div class="form-field">
                <label for="f-message">Votre projet <span aria-hidden="true">*</span></label>
                <textarea id="f-message" name="message" rows="6" required></textarea>
              </div></div>
            </div>
            <button class="btn-primary-as" type="submit">Envoyer la demande</button>
            <p class="form-status" role="status" aria-live="polite"></p>
          </form>
        </div>
      </div>
    </div>
  </section>
""".format(options=options)


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
<meta property="og:title" content="{title}">
<meta property="og:description" content="{description}">
<meta property="og:url" content="{canonical}">
<meta property="og:image" content="{og_image}">
<meta name="theme-color" content="#7d0e7c">
<link rel="icon" href="assets/images/2019/05/Logo-stores-rideaux-maroc.png" type="image/png">
<link rel="preload" as="style" href="assets/css/main.min.css">
<link rel="stylesheet" href="assets/css/main.min.css">
</head>
<body class="{body_class}">
<a id="top"></a>
{header}
<main id="main">
{content}</main>
{footer}
<script src="assets/js/main.min.js" defer></script>
</body>
</html>
"""


def build():
    sitemap = load_sitemap()
    header = header_html()
    footer = footer_html()
    written = []

    for slug, title, parent in PAGES:
        images = sitemap.get(slug, [])
        body = []

        if slug == "home":
            if images:
                body.append(hero_html(images))
            body.append("  <section class=\"section\">\n    <div class=\"container\">\n"
                        "      <h2 class=\"section-title\">%s</h2>\n"
                        "      <p class=\"section-lead\">%s</p>\n%s"
                        "    </div>\n  </section>\n"
                        % (esc(SITE_NAME), esc(SITE_TAGLINE),
                           CONTENT_PENDING.format(slug=slug)))
            body.append(children_cards_html("stores-interieurs", sitemap)
                        .replace("Notre gamme", "Stores Intérieurs"))
            body.append(children_cards_html("stores-exterieurs", sitemap)
                        .replace("Notre gamme", "Stores Extérieurs"))
            body.append(contact_strip())
        elif slug == "devis":
            body.append("  <section class=\"section pb-0\">\n    <div class=\"container\">\n%s"
                        "    </div>\n  </section>\n" % CONTENT_PENDING.format(slug=slug))
            body.append(quote_form())
        else:
            body.append("  <section class=\"section pb-0\">\n    <div class=\"container\">\n%s"
                        "    </div>\n  </section>\n" % CONTENT_PENDING.format(slug=slug))
            body.append(children_cards_html(slug, sitemap))
            body.append(gallery_html(slug, images))
            body.append(contact_strip())

        og = images[0] if images else LOGO.replace("assets/images/", "")
        page_title = ("%s | %s" % (title, SITE_NAME)) if slug != "home" else \
                     ("%s | %s" % (SITE_NAME, SITE_TAGLINE))
        out = PAGE.format(
            title=esc(page_title),
            description=esc("%s - %s, %s." % (title, SITE_NAME, SITE_TAGLINE)),
            canonical="%s/%s" % (SITE_URL, "" if slug == "home" else slug + "/"),
            og_image="%s/assets/images/%s" % (SITE_URL, og if og.startswith("2") else og),
            body_class="page-%s%s" % (slug, " home" if slug == "home" else ""),
            header=header,
            content=page_header_html(slug) + "".join(x for x in body if x),
            footer=footer,
        )
        path = os.path.join(ROOT, href(slug))
        with open(path, "w", encoding="utf-8") as fh:
            fh.write(out)
        written.append((href(slug), len(images)))

    # 404
    not_found = PAGE.format(
        title=esc("Page introuvable | " + SITE_NAME),
        description=esc("La page demandée est introuvable."),
        canonical=SITE_URL + "/404.html",
        og_image="%s/%s" % (SITE_URL, LOGO),
        body_class="page-404",
        header=header,
        content="""  <div class="ovatheme_breadcrumbs">
    <div class="container"><div class="row"><div class="col-md-12">
      <h1 class="page-title">Page introuvable</h1>
      <ul class="breadcrumb"><li><a href="index.html">Accueil</a></li>
      <li class="separator" aria-hidden="true"></li><li aria-current="page">Erreur 404</li></ul>
    </div></div></div>
  </div>
  <section class="section">
    <div class="container">
      <p class="section-lead">La page que vous cherchez n'existe pas ou a été déplacée.</p>
      <p><a class="btn-primary-as" href="index.html">Retour à l'accueil</a></p>
    </div>
  </section>
""",
        footer=footer,
    )
    with open(os.path.join(ROOT, "404.html"), "w", encoding="utf-8") as fh:
        fh.write(not_found)

    print("Generated %d pages + 404.html" % len(written))
    for name, n in written:
        print("  %-34s %2d image(s)" % (name, n))


if __name__ == "__main__":
    build()
