-- ---------------------------------------------------------------------------
-- Alam Stores — schéma de la base de données
--
-- MySQL 5.7+ / MariaDB 10.3+, InnoDB, utf8mb4 (les accents et les emoji
-- passent sans perte). À exécuter une seule fois, puis charger
-- db/seed_categories.sql pour remplir l'arbre des catégories.
--
--   mysql -u <user> -p <base> < db/schema.sql
--   mysql -u <user> -p <base> < db/seed_categories.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- --------------------------------------------------------------- catégories
-- `slug` correspond au nom de la page du site (pergolas -> pergolas.php) :
-- c'est ce qui relie un produit à sa place sur le site. `parent_id` porte les
-- sous-catégories (Stores Enrouleurs -> Store Enrouleur Screen).
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
