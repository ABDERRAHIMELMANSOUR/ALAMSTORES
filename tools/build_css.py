#!/usr/bin/env python3
"""
Assemble assets/css/main.min.css.

Two sources only:

  1. tools/data/fonts-local.css   self-hosted Plus Jakarta Sans @font-face rules
  2. tools/data/design-system.css the site's own stylesheet

The legacy WordPress layer (constrau theme.css, Bootstrap 4, Font Awesome,
ten Flaticon sets, Elegant Icons, Material Design Iconic — ~525 KB of CSS and
1.4 MB of font files) is gone; icons are inline SVG from tools/icons.py.

The purge pass is deliberately conservative: it keeps every class the markup
uses plus the state classes JavaScript toggles at runtime.

Usage:  NODE_TOOLS=/path/with/node_modules python3 tools/build_css.py
"""

import argparse
import os
import subprocess
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA = os.path.join(ROOT, "tools", "data")
CSS_OUT = os.path.join(ROOT, "assets", "css", "main.min.css")

SOURCES = [
    ("fonts", os.path.join(DATA, "fonts-local.css")),
    ("design-system", os.path.join(DATA, "design-system.css")),
]


def build_bundle():
    parts = []
    for label, path in SOURCES:
        if not os.path.exists(path):
            sys.exit("missing stylesheet: %s" % path)
        parts.append("/* ---- %s ---- */\n%s\n" % (label, open(path, encoding="utf-8").read()))
    return "\n".join(parts)


def run_node(script, *args):
    env = dict(os.environ)
    if not env.get("NODE_TOOLS"):
        sys.exit("set NODE_TOOLS to a directory containing node_modules "
                 "(clean-css, purgecss, terser)")
    r = subprocess.run(["node", os.path.join(DATA, script)] + list(args),
                       capture_output=True, text=True, cwd=ROOT, env=env)
    if r.returncode != 0:
        sys.stderr.write(r.stdout + r.stderr)
        sys.exit("node %s failed" % script)
    return r.stdout.strip()


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--no-purge", action="store_true")
    args = ap.parse_args()

    os.makedirs(os.path.dirname(CSS_OUT), exist_ok=True)
    bundle = build_bundle()
    raw = os.path.join(DATA, ".bundle.css")
    open(raw, "w", encoding="utf-8").write(bundle)
    print("bundle: %d bytes" % len(bundle))

    src = raw
    if not args.no_purge:
        purged = os.path.join(DATA, ".bundle.purged.css")
        print(run_node("purge.js", raw, purged))
        src = purged

    print(run_node("minify.js", src, CSS_OUT))

    for f in (raw, os.path.join(DATA, ".bundle.purged.css")):
        if os.path.exists(f):
            os.remove(f)


if __name__ == "__main__":
    main()
