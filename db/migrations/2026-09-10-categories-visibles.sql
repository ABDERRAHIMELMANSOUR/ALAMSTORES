-- Mise à jour d'une installation existante : rend les catégories gérables
-- depuis le back-office (affichage, ordre, renommage, suppression).
--
--   mysql -u <user> -p <base> < db/migrations/2026-09-10-categories-visibles.sql
--
-- Sans effet si elle a déjà été appliquée (l'erreur « Duplicate column name »
-- signifie simplement que c'est déjà fait).

ALTER TABLE categories
  ADD COLUMN is_visible TINYINT(1) NOT NULL DEFAULT 1 AFTER position;

ALTER TABLE categories
  DROP INDEX idx_categories_family,
  ADD KEY idx_categories_family (family, is_visible, position);
