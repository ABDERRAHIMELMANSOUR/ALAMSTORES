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

Contact details are placeholders; see CONTACT below.

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

SITE_NAME = "Alam Stores"
SITE_TAGLINE = "Le Spécialiste de l'Aménagement et de la Décoration"
SITE_URL = "https://alamstores.ma"
LOGO = "assets/images/2019/05/Logo-stores-rideaux-maroc.png"

# ---------------------------------------------------------------------------
# CONTACT DETAILS — PLACEHOLDERS. Replace every value with the real one, then
# re-run `python3 tools/build_pages.py`. These feed the top bar, the drawer,
# the footer, the contact cards and the WhatsApp/e-mail buttons.
#   whatsapp: international format, digits only, no "+" and no spaces.
# ---------------------------------------------------------------------------
CONTACT = {
    "phone_display": "+212 6 00 00 00 00",
    "phone_tel": "+212600000000",
    "whatsapp": "212600000000",
    "email": "contact@alamstores.ma",
    "address": "Casablanca, Maroc",
    "hours": "Lundi – Samedi, 9h – 19h",
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

NAV_TOP = ["home", "societe", "stores-interieurs", "stores-exterieurs",
           "motorisations-automatismes", "service", "partenaires"]

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


# --------------------------------------------------------------------------
# Chrome
# --------------------------------------------------------------------------

def topbar_html():
    return """  <div class="topbar">
    <div class="shell topbar__inner">
      <ul class="topbar__list">
        <li class="is-optional">{pin}<span>{address}</span></li>
        <li>{phone}<a href="tel:{tel}">{phone_display}</a></li>
        <li class="is-optional">{clock}<span>{hours}</span></li>
      </ul>
      <a class="topbar__cta" href="{wa}" target="_blank" rel="noopener">
        {wapp}<span>WhatsApp direct</span>
      </a>
    </div>
  </div>
""".format(pin=icon("pin"), phone=icon("phone"), clock=icon("clock"), wapp=icon("whatsapp"),
           address=esc(CONTACT["address"]), tel=esc(CONTACT["phone_tel"]),
           phone_display=esc(CONTACT["phone_display"]), hours=esc(CONTACT["hours"]),
           wa=esc(wa_link("Bonjour Alam Stores, je souhaite des informations.")))


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
      <a class="btn btn--wa btn--block" href="{wa}" target="_blank" rel="noopener">
        {wapp}<span>WhatsApp direct</span>
      </a>
      <a class="btn btn--primary btn--block" href="devis.html">{spark}<span>Devis gratuit</span></a>
    </div>
  </aside>
""".format(topbar=topbar_html(), logo=LOGO, name=esc(SITE_NAME), tag=esc(SITE_TAGLINE),
           nav=nav_tree_html(), drawer=drawer_tree_html(), close=icon("close"),
           spark=icon("sparkle"), wapp=icon("whatsapp"),
           tel=esc(CONTACT["phone_tel"]), phone_display=esc(CONTACT["phone_display"]),
           email=esc(CONTACT["email"]),
           wa=esc(wa_link("Bonjour Alam Stores, je souhaite des informations.")))


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


def hero_html(images):
    slides = []
    for n, rel in enumerate(images[:5]):
        slides.append(
            '<div class="hero__slide{active}" aria-hidden="{hidden}">'
            '<img src="assets/images/{rel}" alt="" {loading} decoding="async"></div>'.format(
                rel=rel, active=" is-active" if n == 0 else "",
                hidden="false" if n == 0 else "true",
                loading='fetchpriority="high"' if n == 0 else 'loading="lazy"'))

    trust = "".join(
        '<div class="hero__trust-item">{i}<div><strong>{t}</strong><span>{s}</span></div></div>'.format(
            i=icon(ic), t=esc(t), s=esc(s)) for ic, t, s in HERO_TRUST)

    return """  <section class="hero" data-hero aria-roledescription="carrousel" aria-label="Réalisations">
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
          <a class="btn btn--wa btn--lg" href="{wa}" target="_blank" rel="noopener">
            {wapp}<span>WhatsApp direct</span></a>
        </div>
        <div class="hero__trust">{trust}</div>
      </div>
    </div>
    <div class="hero__dots" data-hero-dots></div>
  </section>
""".format(slides="\n      ".join(slides), tagline=esc(SITE_TAGLINE), trust=trust,
           check=icon("check"), truck=icon("truck"), ruler=icon("ruler"),
           spark=icon("sparkle"), wapp=icon("whatsapp"),
           wa=esc(wa_link("Bonjour Alam Stores, je souhaite un devis.")))


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
        ask = wa_link("Bonjour, je souhaite des informations sur : %s." % TITLES[s])
        items.append(
            '<li><article class="card">'
            '{media}'
            '<div class="card__body">'
            '<h3 class="card__title">{title}</h3>'
            '{blurb}'
            '<div class="card__foot">'
            '<span class="card__more">Découvrir {arrow}</span>'
            '<a class="card__ask" href="{ask}" target="_blank" rel="noopener"'
            ' aria-label="Demander des informations sur {title} par WhatsApp">{wapp}<span>Demander</span></a>'
            '</div></div>'
            '<a class="card__link" href="{h}"><span class="sr-only">{title}</span></a>'
            '</article></li>'.format(
                media=media, title=esc(TITLES[s]),
                blurb='<p class="card__text">%s</p>' % esc(blurb) if blurb else "",
                arrow=icon("arrow-right"), ask=esc(ask), wapp=icon("whatsapp"), h=href(s)))
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
        ("whatsapp", "WhatsApp", "Écrire sur WhatsApp", esc(wa_link())),
        ("mail", "E-mail", esc(CONTACT["email"]), "mailto:" + esc(CONTACT["email"])),
        ("clock", "Horaires", esc(CONTACT["hours"]), None),
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
            '<a class="btn btn--wa btn--block" style="margin-top:20px" href="%s" target="_blank" rel="noopener">'
            '%s<span>WhatsApp direct</span></a>'
            "</aside>" % (body, esc(wa_link()), icon("whatsapp")))


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


def gallery_html(slug, images):
    if not images:
        return ""
    items = []
    for n, rel in enumerate(images, 1):
        cap = "%s — photo %d" % (TITLES[slug], n)
        items.append(
            '<li><a href="assets/images/{rel}" data-lightbox="{slug}" data-caption="{cap}">'
            '<img src="assets/images/{rel}" alt="{cap}" loading="lazy" decoding="async">'
            '<span class="gallery__zoom">{zoom}</span></a></li>'.format(
                rel=rel, slug=slug, cap=esc(cap), zoom=icon("zoom")))
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
    items = []
    for rel, name in PARTNER_LOGOS:
        if not os.path.exists(os.path.join(ROOT, "assets", "images", rel)):
            continue
        items.append('<li><div><img src="assets/images/%s" alt="%s" loading="lazy" decoding="async"></div></li>'
                     % (rel, esc(name)))
    if not items:
        return ""
    return """  <section class="section{alt} reveal">
    <div class="shell">
      <div class="section-head">
        <span class="eyebrow">Références</span>
        <h2>Ils nous ont fait confiance</h2>
        <p class="lede">Hôtels, groupes industriels, restaurants et enseignes commerciales
           équipés en stores et aménagements sur mesure.</p>
      </div>
      <div class="partners" data-partners>
        <ul class="partners__track" data-partners-track>
          {items}
        </ul>
        <div class="partners__nav">
          <button class="partners__btn" type="button" data-partners-prev aria-label="Logos précédents">{prev}</button>
          <button class="partners__btn" type="button" data-partners-next aria-label="Logos suivants">{next}</button>
        </div>
      </div>
    </div>
  </section>
""".format(alt=" section--alt" if alt else "", items="\n          ".join(items),
           prev=icon("chevron-left"), next=icon("chevron-right"))


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
            <a class="btn btn--light btn--lg" href="{wa}" target="_blank" rel="noopener">
              {wapp}<span>WhatsApp</span></a>
          </div>
        </div>
      </div>
    </div>
  </section>
""".format(spark=icon("sparkle"), wapp=icon("whatsapp"),
           wa=esc(wa_link("Bonjour Alam Stores, je souhaite un devis.")))


# --------------------------------------------------------------------------
# Quote form
# --------------------------------------------------------------------------

def quote_form():
    options = "".join('<option value="%s">%s</option>' % (esc(TITLES[s]), esc(TITLES[s]))
                      for s in ["stores-interieurs", "stores-exterieurs", "pergolas", "parasols",
                                "moustiquaires", "toiles-tendues", "abris-de-voiture",
                                "motorisations-automatismes"])
    return """  <section class="section">
    <div class="shell split">
      <div>
        <form class="form-card" id="devis-form" data-step-form novalidate
              data-whatsapp="{whatsapp}" data-mailto="{email}">
          <div class="form-card__head">
            <h2>Demande de devis</h2>
            <p>Trois étapes rapides, puis vous choisissez d'envoyer par WhatsApp ou par
               e-mail. Rien n'est enregistré sur ce site.</p>
            <ol class="steps" data-steps>
              <li class="is-active"><span class="steps__num">1</span><span class="steps__label">Projet</span></li>
              <li><span class="steps__num">2</span><span class="steps__label">Détails</span></li>
              <li><span class="steps__num">3</span><span class="steps__label">Coordonnées</span></li>
            </ol>
          </div>

          <div class="form-card__body">
            <fieldset class="fieldset" data-step="0">
              <legend class="fieldset__title">Votre projet</legend>
              <p class="fieldset__hint">Dites-nous ce que vous cherchez à équiper.</p>
              <div class="field-grid">
                <div class="field">
                  <label for="f-product">Produit souhaité</label>
                  <select id="f-product" name="product">{options}</select>
                </div>
                <div class="field">
                  <label for="f-quantity">Nombre d'ouvertures</label>
                  <input id="f-quantity" name="quantity" type="number" min="1" step="1" placeholder="ex. 4">
                </div>
                <div class="field field--full">
                  <label for="f-message">Décrivez votre projet <span class="req" aria-hidden="true">*</span></label>
                  <textarea id="f-message" name="message" rows="5" required
                    placeholder="Pièce concernée, orientation, dimensions approximatives, commande manuelle ou motorisée…"></textarea>
                  <span class="field__error" data-error-for="message"></span>
                </div>
              </div>
            </fieldset>

            <fieldset class="fieldset" data-step="1" hidden>
              <legend class="fieldset__title">Quelques détails</legend>
              <p class="fieldset__hint">Facultatif, mais cela nous aide à répondre plus précisément.</p>
              <div class="field-grid">
                <div class="field">
                  <label for="f-room">Pièce / emplacement</label>
                  <input id="f-room" name="room" type="text" placeholder="Salon, chambre, terrasse…">
                </div>
                <div class="field">
                  <label for="f-orientation">Orientation</label>
                  <select id="f-orientation" name="orientation">
                    <option value="">Je ne sais pas</option>
                    <option>Nord</option><option>Sud</option><option>Est</option><option>Ouest</option>
                  </select>
                </div>
                <div class="field">
                  <label for="f-command">Commande</label>
                  <select id="f-command" name="command">
                    <option value="">À déterminer</option>
                    <option>Manuelle</option>
                    <option>Motorisée</option>
                  </select>
                </div>
                <div class="field">
                  <label for="f-deadline">Échéance souhaitée</label>
                  <input id="f-deadline" name="deadline" type="text" placeholder="ex. sous 1 mois">
                </div>
              </div>
            </fieldset>

            <fieldset class="fieldset" data-step="2" hidden>
              <legend class="fieldset__title">Vos coordonnées</legend>
              <p class="fieldset__hint">Pour que nous puissions vous recontacter.</p>
              <div class="field-grid">
                <div class="field">
                  <label for="f-name">Nom et prénom <span class="req" aria-hidden="true">*</span></label>
                  <input id="f-name" name="name" type="text" required autocomplete="name">
                  <span class="field__error" data-error-for="name"></span>
                </div>
                <div class="field">
                  <label for="f-email">E-mail <span class="req" aria-hidden="true">*</span></label>
                  <input id="f-email" name="email" type="email" required autocomplete="email">
                  <span class="field__error" data-error-for="email"></span>
                </div>
                <div class="field">
                  <label for="f-phone">Téléphone</label>
                  <input id="f-phone" name="phone" type="tel" autocomplete="tel">
                </div>
                <div class="field">
                  <label for="f-city">Ville</label>
                  <input id="f-city" name="city" type="text" autocomplete="address-level2">
                </div>
                <div class="field field--full">
                  <span class="field__error" data-summary-label>Récapitulatif</span>
                  <dl class="summary" data-summary></dl>
                </div>
              </div>
            </fieldset>

            <div class="form-nav">
              <button class="btn btn--ghost" type="button" data-prev hidden>{left}<span>Retour</span></button>
              <span class="spacer"></span>
              <button class="btn btn--primary" type="button" data-next><span>Continuer</span>{right}</button>
              <button class="btn btn--wa" type="submit" data-send="whatsapp" hidden>
                {wapp}<span>Envoyer sur WhatsApp</span></button>
              <button class="btn btn--ghost" type="submit" data-send="mailto" hidden>
                {mail}<span>Par e-mail</span></button>
            </div>
            <p class="form-status" role="status" aria-live="polite"></p>
            <p class="form-note">Le message est composé dans votre application WhatsApp ou
               votre messagerie&nbsp;: vous gardez la main sur l'envoi.</p>
          </div>
        </form>
      </div>
      <div>{aside}</div>
    </div>
  </section>
""".format(options=options, whatsapp=esc(CONTACT["whatsapp"]), email=esc(CONTACT["email"]),
           left=icon("chevron-left"), right=icon("arrow-right"),
           wapp=icon("whatsapp"), mail=icon("mail"), aside=contact_card_html())


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
            <a href="{wa}" target="_blank" rel="noopener" aria-label="WhatsApp">{wapp}</a>
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
          <a href="motorisations-automatismes.html">Motorisations</a>
          <a href="partenaires.html">Partenaires</a>
          <a href="devis.html">Devis</a>
        </nav>
      </div>
    </div>
  </footer>

  <div class="fab">
    <a class="fab__wa" href="{wa}" target="_blank" rel="noopener" aria-label="Nous écrire sur WhatsApp">{wapp}</a>
    <button class="to-top" type="button" id="to-top" aria-label="Retour en haut de page">{up}</button>
  </div>
""".format(logo=LOGO, name=esc(SITE_NAME), tag=esc(SITE_TAGLINE),
           wa=esc(wa_link("Bonjour Alam Stores, je souhaite des informations.")),
           wapp=icon("whatsapp"), mail=icon("mail"), phone=icon("phone"),
           up=icon("arrow-up"),
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
            body.append(prose_section(slug, with_aside=False, skip_lead=True, center=True))
            body.append(quote_form())
        elif slug == "partenaires":
            body.append(page_hero_html(slug))
            body.append(prose_section(slug, skip_lead=True))
            body.append(partners_html(alt=True))
            body.append(gallery_html(slug, images))
            body.append(cta_band())
        else:
            body.append(page_hero_html(slug))
            body.append(prose_section(slug, skip_lead=True))
            body.append(category_section(slug, sitemap, alt=True, blurb=False))
            body.append(gallery_html(slug, images))
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
