-- Généré par tools/build_sql.py — ne pas éditer à la main.
-- Arbre des catégories, aligné sur les pages du site.
-- Rejouable : les catégories existantes sont mises à jour, pas dupliquées.

SET NAMES utf8mb4;

-- 1. les lignes, sans le parent (il peut ne pas exister encore)
INSERT INTO categories (slug, name, family, position) VALUES ('stores-interieurs', 'Stores Intérieurs', 'interieur', 2) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-enrouleurs', 'Stores Enrouleurs', 'interieur', 3) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('store-enrouleur-occultant', 'Store Enrouleur Occultant', 'interieur', 4) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('store-enrouleur-tamisant', 'Store Enrouleur Tamisant', 'interieur', 5) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('store-enrouleur-screen', 'Store Enrouleur Screen', 'interieur', 6) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('store-enrouleur-imprime', 'Store Enrouleur Imprimé', 'interieur', 7) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-venitiens', 'Stores Vénitiens', 'interieur', 8) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('store-venitien-bois', 'Store Vénitien Bois', 'interieur', 9) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('store-venitien-aluminium', 'Store Vénitien Aluminium', 'interieur', 10) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-californiens', 'Stores Californiens', 'interieur', 11) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-bateaux', 'Stores Bateaux', 'interieur', 12) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('store-duo-jour-nuit', 'Store Duo Jour / Nuit', 'interieur', 13) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('panneaux-japonais', 'Panneaux Japonais', 'interieur', 14) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-exterieurs', 'Stores Extérieurs', 'exterieur', 15) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('pergolas', 'Pergolas', 'exterieur', 16) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('parasols', 'Parasols', 'exterieur', 17) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('toiles-tendues', 'Toiles Tendues', 'exterieur', 18) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('abris-de-voiture', 'Abris de Voiture', 'exterieur', 19) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('moustiquaires', 'Moustiquaires', 'exterieur', 20) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);
INSERT INTO categories (slug, name, family, position) VALUES ('motorisations-automatismes', 'Motorisations & Automatismes', 'autre', 21) ON DUPLICATE KEY UPDATE name = VALUES(name), family = VALUES(family), position = VALUES(position);

-- 2. le rattachement parent/enfant, une fois tout inséré
UPDATE categories SET parent_id = NULL WHERE slug = 'stores-interieurs';
UPDATE categories c JOIN categories p ON p.slug = 'stores-interieurs' SET c.parent_id = p.id WHERE c.slug = 'stores-enrouleurs';
UPDATE categories c JOIN categories p ON p.slug = 'stores-enrouleurs' SET c.parent_id = p.id WHERE c.slug = 'store-enrouleur-occultant';
UPDATE categories c JOIN categories p ON p.slug = 'stores-enrouleurs' SET c.parent_id = p.id WHERE c.slug = 'store-enrouleur-tamisant';
UPDATE categories c JOIN categories p ON p.slug = 'stores-enrouleurs' SET c.parent_id = p.id WHERE c.slug = 'store-enrouleur-screen';
UPDATE categories c JOIN categories p ON p.slug = 'stores-enrouleurs' SET c.parent_id = p.id WHERE c.slug = 'store-enrouleur-imprime';
UPDATE categories c JOIN categories p ON p.slug = 'stores-interieurs' SET c.parent_id = p.id WHERE c.slug = 'stores-venitiens';
UPDATE categories c JOIN categories p ON p.slug = 'stores-venitiens' SET c.parent_id = p.id WHERE c.slug = 'store-venitien-bois';
UPDATE categories c JOIN categories p ON p.slug = 'stores-venitiens' SET c.parent_id = p.id WHERE c.slug = 'store-venitien-aluminium';
UPDATE categories c JOIN categories p ON p.slug = 'stores-interieurs' SET c.parent_id = p.id WHERE c.slug = 'stores-californiens';
UPDATE categories c JOIN categories p ON p.slug = 'stores-interieurs' SET c.parent_id = p.id WHERE c.slug = 'stores-bateaux';
UPDATE categories c JOIN categories p ON p.slug = 'stores-interieurs' SET c.parent_id = p.id WHERE c.slug = 'store-duo-jour-nuit';
UPDATE categories c JOIN categories p ON p.slug = 'stores-interieurs' SET c.parent_id = p.id WHERE c.slug = 'panneaux-japonais';
UPDATE categories SET parent_id = NULL WHERE slug = 'stores-exterieurs';
UPDATE categories c JOIN categories p ON p.slug = 'stores-exterieurs' SET c.parent_id = p.id WHERE c.slug = 'pergolas';
UPDATE categories c JOIN categories p ON p.slug = 'stores-exterieurs' SET c.parent_id = p.id WHERE c.slug = 'parasols';
UPDATE categories c JOIN categories p ON p.slug = 'stores-exterieurs' SET c.parent_id = p.id WHERE c.slug = 'toiles-tendues';
UPDATE categories c JOIN categories p ON p.slug = 'stores-exterieurs' SET c.parent_id = p.id WHERE c.slug = 'abris-de-voiture';
UPDATE categories c JOIN categories p ON p.slug = 'stores-exterieurs' SET c.parent_id = p.id WHERE c.slug = 'moustiquaires';
UPDATE categories SET parent_id = NULL WHERE slug = 'motorisations-automatismes';
