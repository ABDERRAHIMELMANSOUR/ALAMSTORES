-- ===========================================================================
-- ALAM STORES — création de la base de données
--
-- UN SEUL FICHIER À IMPORTER. Il crée les 7 tables et les 20 catégories.
--
-- COMMENT L'IMPORTER
--   1. Panneau de votre hébergeur -> phpMyAdmin
--   2. Cliquez sur le NOM DE VOTRE BASE dans la colonne de gauche
--      (important : la base, pas le serveur tout en haut)
--   3. Onglet « Importer » -> « Choisir un fichier » -> ce fichier
--   4. Tout en bas : « Exécuter »
--
-- Vous devez voir « L'importation s'est terminée avec succès ».
--
-- REJOUABLE : l'importer deux fois ne perd ni ne duplique rien
-- (CREATE TABLE IF NOT EXISTS + ON DUPLICATE KEY UPDATE).
--
-- IL NE CRÉE PAS VOTRE COMPTE : un mot de passe n'a rien à faire dans un
-- fichier SQL. Ouvrez ensuite https://votre-domaine/admin/setup.php pour le
-- créer — l'installateur verra que les tables sont déjà là.
--
-- Compatible MySQL 5.7+ et MariaDB 10.3+. InnoDB, utf8mb4 : les accents
-- passent sans perte.
-- ===========================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
CREATE TABLE IF NOT EXISTS categories (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug        VARCHAR(120)  NOT NULL,
  name        VARCHAR(180)  NOT NULL,
  parent_id   INT UNSIGNED  NULL,
  family      ENUM('interieur','exterieur','autre') NOT NULL DEFAULT 'autre',
  position    SMALLINT      NOT NULL DEFAULT 0,
  -- Masquer une catégorie la retire du menu, du pied de page et de la page
  -- d'accueil, et met sa page en 404. Les produits ne sont pas supprimés.
  is_visible  TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_categories_slug (slug),
  KEY idx_categories_parent (parent_id),
  KEY idx_categories_family (family, is_visible, position),
  CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id)
    REFERENCES categories (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------ produits
-- `parent_id` NULL = produit principal ; renseigné = sous-produit (une
-- déclinaison rattachée à un produit, qui hérite de sa catégorie).
-- Obligatoires : title, description et au moins une image (contrôlé côté
-- application, cf. api/admin/products.php). video_url est facultatif.
CREATE TABLE IF NOT EXISTS products (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category_id  INT UNSIGNED NOT NULL,
  parent_id    INT UNSIGNED NULL,
  slug         VARCHAR(190) NOT NULL,
  title        VARCHAR(200) NOT NULL,
  description  TEXT         NOT NULL,
  video_url    VARCHAR(500) NULL,
  is_published TINYINT(1)   NOT NULL DEFAULT 1,
  position     SMALLINT     NOT NULL DEFAULT 0,
  created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_products_slug (slug),
  KEY idx_products_category (category_id, is_published, position),
  KEY idx_products_parent (parent_id, position),
  CONSTRAINT fk_products_category FOREIGN KEY (category_id)
    REFERENCES categories (id) ON DELETE CASCADE,
  CONSTRAINT fk_products_parent FOREIGN KEY (parent_id)
    REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------- photos
-- `path` est relatif à la racine du site (assets/uploads/photos/xxx.webp).
CREATE TABLE IF NOT EXISTS product_images (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED NOT NULL,
  path       VARCHAR(300) NOT NULL,
  alt        VARCHAR(200) NOT NULL DEFAULT '',
  position   SMALLINT     NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_images_product (product_id, position),
  CONSTRAINT fk_images_product FOREIGN KEY (product_id)
    REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------- fiches techniques
-- Facultatif : un produit peut n'en avoir aucune.
CREATE TABLE IF NOT EXISTS product_docs (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED NOT NULL,
  path       VARCHAR(300) NOT NULL,
  label      VARCHAR(200) NOT NULL DEFAULT 'Fiche technique (PDF)',
  size_bytes INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_docs_product (product_id),
  CONSTRAINT fk_docs_product FOREIGN KEY (product_id)
    REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------- caractéristiques techniques
CREATE TABLE IF NOT EXISTS product_specs (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED NOT NULL,
  label      VARCHAR(160) NOT NULL,
  value      VARCHAR(300) NOT NULL,
  position   SMALLINT     NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_specs_product (product_id, position),
  CONSTRAINT fk_specs_product FOREIGN KEY (product_id)
    REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------- leads
-- Demandes de devis envoyées depuis devis.html. `ip_hash` est un HMAC
-- tronqué : il permet de limiter le spam sans conserver d'adresse IP en clair.
CREATE TABLE IF NOT EXISTS leads (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  statut     VARCHAR(80)  NOT NULL DEFAULT '',
  company    VARCHAR(180) NOT NULL DEFAULT '',
  category   VARCHAR(120) NOT NULL DEFAULT '',
  firstname  VARCHAR(120) NOT NULL DEFAULT '',
  lastname   VARCHAR(120) NOT NULL DEFAULT '',
  email      VARCHAR(190) NOT NULL DEFAULT '',
  phone      VARCHAR(60)  NOT NULL DEFAULT '',
  address    VARCHAR(240) NOT NULL DEFAULT '',
  zip        VARCHAR(20)  NOT NULL DEFAULT '',
  city       VARCHAR(120) NOT NULL DEFAULT '',
  country    VARCHAR(120) NOT NULL DEFAULT '',
  message    TEXT         NOT NULL,
  source     VARCHAR(40)  NOT NULL DEFAULT 'devis.html',
  ip_hash    CHAR(32)     NOT NULL DEFAULT '',
  user_agent VARCHAR(255) NOT NULL DEFAULT '',
  is_read    TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_leads_created (created_at),
  KEY idx_leads_read (is_read, created_at),
  KEY idx_leads_ip (ip_hash, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------- comptes du back-office
-- Aucun mot de passe n'est stocké : uniquement un hash password_hash().
-- Créez le premier compte avec  php tools/make_admin.php <identifiant>
CREATE TABLE IF NOT EXISTS admin_users (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username      VARCHAR(80)  NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login_at DATETIME     NULL,
  failed_count  SMALLINT     NOT NULL DEFAULT 0,
  locked_until  DATETIME     NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_admin_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================================
-- Les catégories : deux familles, Intérieur et Extérieur, avec leurs
-- sous-catégories. Vous les gérerez ensuite depuis l'onglet Catégories du
-- back-office — renommer, masquer, réordonner, ajouter.
-- ===========================================================================
INSERT INTO categories (slug, name, family, position) VALUES ('stores-interieurs', 'Stores Intérieurs', 'interieur', 2) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-enrouleurs', 'Stores Enrouleurs', 'interieur', 3) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('store-enrouleur-occultant', 'Store Enrouleur Occultant', 'interieur', 4) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('store-enrouleur-tamisant', 'Store Enrouleur Tamisant', 'interieur', 5) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('store-enrouleur-screen', 'Store Enrouleur Screen', 'interieur', 6) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('store-enrouleur-imprime', 'Store Enrouleur Imprimé', 'interieur', 7) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-venitiens', 'Stores Vénitiens', 'interieur', 8) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('store-venitien-bois', 'Store Vénitien Bois', 'interieur', 9) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('store-venitien-aluminium', 'Store Vénitien Aluminium', 'interieur', 10) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-californiens', 'Stores Californiens', 'interieur', 11) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-bateaux', 'Stores Bateaux', 'interieur', 12) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('store-duo-jour-nuit', 'Store Duo Jour / Nuit', 'interieur', 13) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('panneaux-japonais', 'Panneaux Japonais', 'interieur', 14) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('stores-exterieurs', 'Stores Extérieurs', 'exterieur', 15) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('pergolas', 'Pergolas', 'exterieur', 16) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('parasols', 'Parasols', 'exterieur', 17) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('toiles-tendues', 'Toiles Tendues', 'exterieur', 18) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('abris-de-voiture', 'Abris de Voiture', 'exterieur', 19) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('moustiquaires', 'Moustiquaires', 'exterieur', 20) ON DUPLICATE KEY UPDATE family = VALUES(family);
INSERT INTO categories (slug, name, family, position) VALUES ('motorisations-automatismes', 'Motorisations & Automatismes', 'autre', 21) ON DUPLICATE KEY UPDATE family = VALUES(family);

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
