#!/usr/bin/env python3
"""
Restore the original page copy into the static site.

The WordPress database was not part of the export, so the generated pages ship
with `.content-pending` placeholders. Run this from any machine that can reach
alamstores.ma (or point it at a local WordPress copy) and it will:

  1. fetch each page listed in tools/build_pages.py,
  2. pull the main content region out of the response,
  3. strip scripts, tracking, inline handlers and the injected SEO-spam block,
  4. download any referenced image that is not already in assets/images,
  5. rewrite wp-content/uploads URLs and internal links to the static paths,
  6. replace the placeholder in the matching local .html file.

Only the standard library is required.

    python3 tools/fetch_content.py                 # restore every page
    python3 tools/fetch_content.py pergolas devis  # restore selected pages
    python3 tools/fetch_content.py --base https://staging.example.com
    python3 tools/fetch_content.py --dry-run
"""

import argparse
import os
import re
import sys
import urllib.error
import urllib.parse
import urllib.request
from html.parser import HTMLParser

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from build_pages import PAGES, TITLES, href  # noqa: E402

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMAGES = os.path.join(ROOT, "assets", "images")
UA = "Mozilla/5.0 (compatible; alamstores-static-export/1.0)"

# Containers to try, in order, when locating the page body.
CONTENT_HINTS = [
    ("div", "elementor"),
    ("div", "entry-content"),
    ("div", "page-content"),
    ("article", None),
    ("main", None),
]

# Never carried across into the static site.
DROP_TAGS = {
    "script", "noscript", "style", "iframe", "object", "embed", "form",
    "link", "meta", "svg", "canvas", "template",
}
# Markers of the injected footer-links / SEO-spam block documented in
# SECURITY-AUDIT.md - dropped wherever it appears.
SPAM_MARKERS = re.compile(
    r"flm[-_]|footer[-_]links|casino|bookmaker|betting|gambling|slots?\b",
    re.I,
)
SAFE_ATTRS = {
    "href", "src", "srcset", "sizes", "alt", "title", "class", "id",
    "width", "height", "loading", "decoding", "target", "rel", "colspan",
    "rowspan", "datetime", "cite",
}
VOID = {"img", "br", "hr", "input", "source", "col", "wbr", "area"}


class ContentExtractor(HTMLParser):
    """Pull the first matching content container out and sanitise it."""

    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.stack = []
        self.capture_depth = None
        self.skip_depth = None
        self.out = []
        self.found = False

    # -- helpers ---------------------------------------------------------
    @staticmethod
    def _classes(attrs):
        return dict(attrs).get("class", "") or ""

    def _matches_hint(self, tag, attrs):
        cls = self._classes(attrs)
        for want_tag, want_cls in CONTENT_HINTS:
            if tag != want_tag:
                continue
            if want_cls is None or want_cls in cls.split():
                return True
        return False

    def _emit(self, s):
        if self.capture_depth is not None and self.skip_depth is None:
            self.out.append(s)

    # -- parser callbacks ------------------------------------------------
    def handle_starttag(self, tag, attrs):
        depth = len(self.stack)
        self.stack.append(tag)

        if self.capture_depth is None and not self.found and self._matches_hint(tag, attrs):
            self.capture_depth = depth
            self.found = True
            return  # the container itself is not emitted

        if self.capture_depth is None:
            return

        if self.skip_depth is None:
            attr_blob = " ".join("%s=%s" % (k, v or "") for k, v in attrs)
            if tag in DROP_TAGS or SPAM_MARKERS.search(attr_blob):
                self.skip_depth = depth
                if tag in VOID:
                    self.skip_depth = None
                    self.stack.pop()
                return

        if self.skip_depth is not None:
            return

        kept = []
        for k, v in attrs:
            k = k.lower()
            if k not in SAFE_ATTRS or v is None:
                continue
            if k in ("href", "src") and v.strip().lower().startswith(("javascript:", "data:text")):
                continue
            kept.append('%s="%s"' % (k, v.replace('"', "&quot;")))
        self._emit("<%s%s>" % (tag, (" " + " ".join(kept)) if kept else ""))
        if tag in VOID:
            self.stack.pop()

    def handle_endtag(self, tag):
        if tag in VOID:
            return
        while self.stack and self.stack[-1] != tag:
            self.stack.pop()
        depth = len(self.stack) - 1 if self.stack else 0

        if self.skip_depth is not None and depth == self.skip_depth:
            self.skip_depth = None
            if self.stack:
                self.stack.pop()
            return
        if self.capture_depth is not None and depth == self.capture_depth:
            self.capture_depth = None
            if self.stack:
                self.stack.pop()
            return
        self._emit("</%s>" % tag)
        if self.stack:
            self.stack.pop()

    def handle_data(self, data):
        self._emit(data.replace("<", "&lt;"))


# ----------------------------------------------------------------------
# Rewriting
# ----------------------------------------------------------------------

SLUG_BY_PATH = {}
for _slug, _t, _p in PAGES:
    SLUG_BY_PATH["" if _slug == "home" else _slug] = _slug


def fetch(url, timeout=30):
    req = urllib.request.Request(url, headers={"User-Agent": UA})
    with urllib.request.urlopen(req, timeout=timeout) as r:
        raw = r.read()
    charset = "utf-8"
    ctype = r.headers.get("Content-Type", "")
    m = re.search(r"charset=([\w-]+)", ctype)
    if m:
        charset = m.group(1)
    return raw.decode(charset, errors="replace")


def download_image(url, timeout=60):
    """Save an uploads image under assets/images, return its relative path."""
    m = re.search(r"/wp-content/uploads/(.+)$", urllib.parse.urlparse(url).path)
    if not m:
        return None
    rel = m.group(1)
    dst = os.path.join(IMAGES, rel)
    if os.path.exists(dst):
        return rel
    os.makedirs(os.path.dirname(dst), exist_ok=True)
    try:
        req = urllib.request.Request(url, headers={"User-Agent": UA})
        with urllib.request.urlopen(req, timeout=timeout) as r:
            data = r.read()
        with open(dst, "wb") as fh:
            fh.write(data)
        return rel
    except (urllib.error.URLError, OSError) as e:
        print("      ! image failed: %s (%s)" % (url, e))
        return None


def rewrite(fragment, base, download=True):
    """Point uploads URLs at assets/images and internal links at .html files."""
    stats = {"images": 0, "links": 0}

    def img_sub(m):
        url = m.group(2)
        rel = download_image(urllib.parse.urljoin(base, url)) if download else None
        if not rel:
            m2 = re.search(r"/wp-content/uploads/(.+)$", url)
            rel = m2.group(1) if m2 else None
        if not rel:
            return m.group(0)
        stats["images"] += 1
        return "%s=\"assets/images/%s\"" % (m.group(1), rel)

    fragment = re.sub(
        r'\b(src|href)="([^"]*?/wp-content/uploads/[^"]+)"', img_sub, fragment
    )
    # srcset lists
    def srcset_sub(m):
        parts = []
        for cand in m.group(1).split(","):
            cand = cand.strip()
            if not cand:
                continue
            bits = cand.split()
            rel = download_image(urllib.parse.urljoin(base, bits[0])) if download else None
            if not rel:
                m2 = re.search(r"/wp-content/uploads/(.+)$", bits[0])
                rel = m2.group(1) if m2 else None
            if rel:
                bits[0] = "assets/images/" + rel
            parts.append(" ".join(bits))
        return 'srcset="%s"' % ", ".join(parts)

    fragment = re.sub(r'srcset="([^"]+)"', srcset_sub, fragment)

    # Internal page links -> local .html. The production domain counts as
    # internal even when reading from a staging host or a local mirror.
    internal_hosts = {urllib.parse.urlparse(base).netloc, "alamstores.ma", "www.alamstores.ma"}

    def link_sub(m):
        url = m.group(1)
        parsed = urllib.parse.urlparse(urllib.parse.urljoin(base, url))
        if parsed.netloc and parsed.netloc not in internal_hosts:
            return m.group(0)
        path = parsed.path.strip("/")
        if path in SLUG_BY_PATH:
            stats["links"] += 1
            return 'href="%s"' % href(SLUG_BY_PATH[path])
        return m.group(0)

    fragment = re.sub(r'href="([^"]+)"', link_sub, fragment)

    # Drop leftover WP theme/plugin asset URLs.
    fragment = re.sub(r'\b(src|href)="[^"]*?/wp-(content|includes)/(?!uploads)[^"]*"', "", fragment)
    return fragment, stats


def tidy(fragment):
    fragment = re.sub(r"<(\w+)[^>]*>\s*</\1>", "", fragment)   # empty elements
    fragment = re.sub(r"\n{3,}", "\n\n", fragment)
    fragment = re.sub(r"[ \t]+\n", "\n", fragment)
    return fragment.strip()


PLACEHOLDER = re.compile(
    r'<div class="content-pending" data-content-slot="[^"]*">.*?</div>\s*',
    re.S,
)


def main():
    ap = argparse.ArgumentParser(description=__doc__,
                                 formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument("slugs", nargs="*", help="only these page slugs (default: all)")
    ap.add_argument("--base", default="https://alamstores.ma",
                    help="site to read from (default: https://alamstores.ma)")
    ap.add_argument("--dry-run", action="store_true",
                    help="report what would change without writing files")
    ap.add_argument("--no-images", action="store_true",
                    help="rewrite image paths but do not download missing files")
    args = ap.parse_args()

    base = args.base.rstrip("/") + "/"
    targets = [(s, t) for s, t, _ in PAGES if not args.slugs or s in args.slugs]
    if args.slugs:
        unknown = set(args.slugs) - {s for s, _, _ in PAGES}
        if unknown:
            sys.exit("unknown slug(s): %s" % ", ".join(sorted(unknown)))

    ok = failed = skipped = 0
    for slug, title in targets:
        url = base if slug == "home" else base + slug + "/"
        local = os.path.join(ROOT, href(slug))
        print("-> %-32s %s" % (slug, url))

        try:
            page = fetch(url)
        except (urllib.error.URLError, OSError) as e:
            print("   ! fetch failed: %s" % e)
            failed += 1
            continue

        ex = ContentExtractor()
        ex.feed(page)
        fragment = tidy("".join(ex.out))
        if not ex.found or len(fragment) < 80:
            print("   ! no usable content region found - left unchanged")
            failed += 1
            continue

        fragment, stats = rewrite(fragment, url, download=not args.no_images)
        fragment = tidy(fragment)

        html_doc = open(local, encoding="utf-8").read()
        if not PLACEHOLDER.search(html_doc):
            print("   . no placeholder left in %s - skipped" % href(slug))
            skipped += 1
            continue

        block = '<div class="restored-content">\n%s\n      </div>\n' % fragment
        updated = PLACEHOLDER.sub(lambda _: block, html_doc, count=1)

        print("   ok %d chars, %d image(s), %d link(s) rewritten"
              % (len(fragment), stats["images"], stats["links"]))
        if not args.dry_run:
            open(local, "w", encoding="utf-8").write(updated)
        ok += 1

    print("\nrestored %d, skipped %d, failed %d" % (ok, skipped, failed))
    if ok and not args.dry_run:
        print("Now re-run:  python3 tools/build_css.py   "
              "(re-purges CSS against the restored markup)")


if __name__ == "__main__":
    main()
