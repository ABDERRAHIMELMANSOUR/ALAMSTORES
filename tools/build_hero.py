#!/usr/bin/env python3
"""
Re-encode the home carousel photography.

Takes the highest-resolution originals from assets/images and writes
1920x1080 WebP versions into assets/images/hero/. The originals stay untouched.

The carousel is images only — the site carries no video anywhere, by design.

Usage:  python3 tools/build_hero.py        (needs Pillow)
"""

import os
import sys

try:
    from PIL import Image
except ImportError:
    sys.exit("Pillow is required:  pip install pillow")

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMAGES = os.path.join(ROOT, "assets", "images")
OUT_DIR = os.path.join(IMAGES, "hero")

WIDTH, HEIGHT, QUALITY = 1920, 1080, 74

# source (relative to assets/images) -> output basename
SOURCES = [
    ("2019/05/slide-2.jpg",                                 "hero-pergola-piscine"),
    ("2022/04/pratic21.jpg",                                "hero-store-banne-terrasse"),
    ("2022/04/image7.jpg",                                  "hero-venitiens-bois-bureau"),
    ("2022/04/japon5.jpg",                                  "hero-stores-interieurs-salon"),
    ("2022/04/29514043_10155071876206619_1548485636_o.jpg", "hero-stores-exterieurs-facade"),
]


def main():
    os.makedirs(OUT_DIR, exist_ok=True)
    total = 0
    for rel, name in SOURCES:
        src = os.path.join(IMAGES, rel)
        if not os.path.exists(src):
            print("  ! missing source:", rel)
            continue
        im = Image.open(src).convert("RGB")
        sw, sh = im.size
        scale = max(WIDTH / sw, HEIGHT / sh)
        im = im.resize((round(sw * scale), round(sh * scale)), Image.LANCZOS)
        left = (im.width - WIDTH) // 2
        top = max(0, (im.height - HEIGHT) // 2)
        im = im.crop((left, top, left + WIDTH, top + HEIGHT))

        out = os.path.join(OUT_DIR, name + ".webp")
        im.save(out, "WEBP", quality=QUALITY, method=6)
        kb = os.path.getsize(out) // 1024
        total += kb
        print("  %4d KB  %s.webp  <- %s (%dx%d)" % (kb, name, rel, sw, sh))
    print("total: %d KB (only the first slide loads eagerly)" % total)


if __name__ == "__main__":
    main()
