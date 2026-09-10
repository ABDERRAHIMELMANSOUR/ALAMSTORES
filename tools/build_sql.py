#!/usr/bin/env python3
"""Regenerate db/seed_categories.sql from the page tree in build_pages.py.

The category slugs in the database are the page slugs: that is the whole
mechanism that puts a product on the right page. Re-run this whenever PAGES
changes, then re-import the file — it is an idempotent upsert, so it can be
replayed over an existing database without losing products.

    python3 tools/build_sql.py
    mysql -u <user> -p <base> < db/seed_categories.sql
"""

import io
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import build_pages as B  # noqa: E402

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, "db", "seed_categories.sql")

# Pages that are not product categories — nothing is ever filed under them.
SKIP = {"home", "societe", "service", "partenaires", "devis"}

FAMILY = {"stores-interieurs": "interieur", "stores-exterieurs": "exterieur"}


def family(slug):
    """Inherited from the top-level ancestor, so sub-pages land in the right group."""
    return FAMILY.get(B.top_ancestor(slug), "autre")


def sql_str(value):
    if value is None:
        return "NULL"
    return "'" + value.replace("\\", "\\\\").replace("'", "''") + "'"


def main():
    rows = [(slug, B.TITLES[slug], parent, family(slug), i)
            for i, (slug, _title, parent) in enumerate(B.PAGES)
            if slug not in SKIP]

    lines = [
        "-- Généré par tools/build_sql.py — ne pas éditer à la main.",
        "-- Arbre des catégories, aligné sur les pages du site.",
        "-- Rejouable : les catégories existantes sont mises à jour, pas dupliquées.",
        "",
        "SET NAMES utf8mb4;",
        "",
        "-- 1. les lignes, sans le parent (il peut ne pas exister encore)",
    ]
    for slug, name, _parent, fam, pos in rows:
        lines.append(
            # Seule `family` est mise à jour : le nom, l'ordre et la visibilité
            # appartiennent au back-office une fois la catégorie créée, et
            # rejouer la graine ne doit pas défaire ce qui y a été réglé.
            "INSERT INTO categories (slug, name, family, position) VALUES "
            "(%s, %s, %s, %d) ON DUPLICATE KEY UPDATE family = VALUES(family);"
            % (sql_str(slug), sql_str(name), sql_str(fam), pos))

    lines += ["", "-- 2. le rattachement parent/enfant, une fois tout inséré"]
    for slug, _name, parent, _fam, _pos in rows:
        if parent and parent not in SKIP:
            lines.append(
                "UPDATE categories c JOIN categories p ON p.slug = %s "
                "SET c.parent_id = p.id WHERE c.slug = %s;"
                % (sql_str(parent), sql_str(slug)))
        else:
            lines.append("UPDATE categories SET parent_id = NULL WHERE slug = %s;"
                         % sql_str(slug))

    lines.append("")
    io.open(OUT, "w", encoding="utf-8", newline="\n").write("\n".join(lines))
    print("db/seed_categories.sql: %d catégories" % len(rows))


if __name__ == "__main__":
    main()
