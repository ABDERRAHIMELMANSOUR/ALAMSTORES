# Installation d'Alam Stores sur votre hébergement

Guide complet, de l'archive téléchargée au site en ligne avec son back-office.
Comptez **45 minutes**, dont l'essentiel en attente du transfert FTP.

**Ce dont vous avez besoin :** un hébergement mutualisé classique (OVH, LWS,
Hostinger, Genious, o2switch…) avec **PHP 7.4 ou plus** et **MySQL 5.7 / MariaDB
10.3 ou plus**, vos identifiants FTP, et [FileZilla](https://filezilla-project.org/)
installé.

---

## Sommaire

1. [Télécharger la version finale](#1-télécharger-la-version-finale)
2. [Créer la base de données](#2-créer-la-base-de-données)
3. [Envoyer les fichiers avec FileZilla](#3-envoyer-les-fichiers-avec-filezilla)
4. [Lancer l'installateur](#4-lancer-linstallateur)
5. [Supprimer l'installateur](#5-supprimer-linstallateur--obligatoire)
6. [Vérifier que tout fonctionne](#6-vérifier-que-tout-fonctionne)
7. [Sécuriser](#7-sécuriser)
8. [Utiliser le back-office au quotidien](#8-utiliser-le-back-office-au-quotidien)
9. [En cas de problème](#9-en-cas-de-problème)

---

## 1. Télécharger la version finale

<https://github.com/ABDERRAHIMELMANSOUR/ALAMSTORES/archive/refs/heads/main.zip>

Si le lien direct ne fonctionne pas (dépôt privé, session expirée), passez par
l'interface : ouvrez
<https://github.com/ABDERRAHIMELMANSOUR/ALAMSTORES>, cliquez sur le bouton vert
**Code**, puis **Download ZIP**.

Décompressez l'archive. Elle contient un dossier `ALAMSTORES-main` : **c'est le
contenu de ce dossier** que vous enverrez, pas le dossier lui-même.

> La branche `main` est toujours la version à jour. Si vous réinstallez plus
> tard, retéléchargez depuis ce même lien.

Ce qu'il y a dedans :

| Dossier / fichier | À envoyer sur le serveur ? | Rôle |
|---|---|---|
| `index.php` et les 24 autres `.php` | **oui** | les pages du site |
| `assets/` | **oui** | images, styles, script, polices |
| `includes/` | **oui** | l'en-tête, le pied de page et le catalogue partagés |
| `admin/` | **oui** | le back-office |
| `api/` | **oui** | les échanges avec la base |
| `db/` | **oui** | le schéma SQL, lu par l'installateur |
| `.htaccess`, `robots.txt`, `sitemap.xml` | **oui** | réglages serveur et référencement |
| `tools/` | facultatif | scripts de build + le scanner de sécurité |
| `README.md`, `INSTALLATION.md`, `SECURITY-AUDIT.md` | non | documentation |

> `assets/images/` pèse environ **110 Mo**. C'est le gros du transfert.

---

## 2. Créer la base de données

Dans le panneau de votre hébergeur (cPanel, Plesk ou l'interface maison) :

1. Cherchez **« Bases de données MySQL »**.
2. **Créez une base** — par exemple `alamstores`. L'hébergeur y ajoute souvent
   un préfixe : le nom réel devient quelque chose comme `alamst_alamstores`.
   **Notez le nom complet.**
3. **Créez un utilisateur** — par exemple `alamstores_admin`, avec un mot de
   passe long généré par le panneau. **Notez-le.**
4. **Rattachez l'utilisateur à la base** et cochez **ALL PRIVILEGES**.

> **Un seul conseil de sécurité ici :** cet utilisateur ne doit avoir de droits
> que sur **cette** base. Pas de `FILE`, pas de `GRANT`, aucun accès aux autres
> bases de votre compte. C'est ce qui limite les dégâts si le site est un jour
> compromis.

Vous repartez avec quatre valeurs :

```
Serveur   : localhost        (ou ce que l'hébergeur indique)
Base      : alamst_alamstores
Utilisateur : alamst_admin
Mot de passe : ••••••••••••
```

**Vous n'avez rien d'autre à faire ici.** Les tables seront créées à l'étape 4 —
inutile de passer par phpMyAdmin.

---

## 3. Envoyer les fichiers avec FileZilla

### Se connecter

Dans FileZilla, **Fichier → Gestionnaire de sites → Nouveau site** :

| Champ | Valeur |
|---|---|
| Protocole | **SFTP** si votre hébergeur le propose, sinon **FTP** |
| Hôte | `ftp.alamstores.ma` (ou l'adresse donnée par l'hébergeur) |
| Chiffrement | **Connexion FTP explicite sur TLS** (si FTP) |
| Identifiant / Mot de passe | ceux de votre compte FTP |

> Évitez le FTP simple sans TLS : le mot de passe circule alors en clair.

### Envoyer

1. À droite (le serveur), ouvrez le dossier public : `public_html`, `www` ou
   `httpdocs` selon l'hébergeur.
2. **Si un ancien site WordPress s'y trouve, ne le mélangez pas avec celui-ci.**
   Renommez-le d'abord en `ancien-site` — vous le supprimerez une fois le
   nouveau site vérifié. (Voir `SECURITY-AUDIT.md` : l'ancienne installation
   était infectée, ne recopiez rien depuis elle.)
3. À gauche (votre ordinateur), ouvrez le dossier `ALAMSTORES-main`.
4. Sélectionnez **tout son contenu**, puis glissez-le vers la droite.
5. Vérifiez que **`.htaccess` est bien parti**. FileZilla masque parfois les
   fichiers commençant par un point : **Serveur → Forcer l'affichage des
   fichiers cachés**. Sans ce fichier, les URL sans extension et une partie des
   protections ne fonctionnent pas.

Le transfert dure de 15 minutes à une heure selon votre connexion. Laissez
FileZilla ouvert ; s'il signale des échecs, faites un clic droit sur la file
d'attente → **Retransférer les fichiers échoués**.

### Vérifier les permissions

Clic droit sur un dossier → **Droits d'accès au fichier** :

| Élément | Valeur |
|---|---|
| Dossiers | `755` |
| Fichiers | `644` |
| `assets/uploads/photos` et `assets/uploads/docs` | `755` (le serveur doit pouvoir y écrire) |

---

## 4. Lancer l'installateur

> **Vous aviez déjà installé une version précédente ?** Ne relancez pas
> l'installateur : importez seulement la mise à jour de la base, une fois,
> depuis phpMyAdmin (onglet **Importer**) ou en ligne de commande :
>
> ```bash
> mysql -u <user> -p <base> < db/migrations/2026-09-10-categories-visibles.sql
> ```
>
> Elle ajoute la colonne dont l'onglet **Catégories** a besoin. Une erreur
> « Duplicate column name » signifie simplement que c'est déjà fait.


Ouvrez dans votre navigateur :

```
https://alamstores.ma/admin/setup.php
```

Trois étapes s'enchaînent.

**Étape 1 — Base de données.** Recopiez les quatre valeurs de l'étape 2, et les
adresses e-mail qui doivent recevoir les demandes de devis (elles sont
pré-remplies avec `contact@alamstores.ma` et `kassettebrahim.1997@gmail.com`).

La même page demande les **clés reCAPTCHA**. La clé du site est déjà remplie ;
collez la **clé secrète** depuis
[google.com/recaptcha/admin](https://www.google.com/recaptcha/admin) et choisissez
le type — **v2** si votre clé affiche une case « Je ne suis pas un robot »,
**v3** si elle est invisible. En cas de doute, laissez v2 : si la case
n'apparaît pas sur `/devis`, revenez ici et passez en v3.

Vous pouvez laisser la clé secrète vide pour l'instant : le formulaire
fonctionne, simplement sans filtrage anti-robot, et vous ajouterez la clé plus
tard dans `api/config.php`.

Cliquez sur **Tester et enregistrer**.

- Si tout va bien, le fichier `api/config.php` est créé automatiquement.
- Si la page affiche « n'a pas pu être écrit », elle vous montre le contenu
  exact du fichier : copiez-le dans un fichier nommé `config.php`, envoyez-le
  dans `api/` avec FileZilla, puis rechargez la page.

**Étape 2 — Tables.** Un bouton, rien à saisir. Il crée les sept tables et
importe l'arbre des catégories (les 20 catégories correspondent aux pages du
site). Vous les gérerez ensuite depuis l'onglet **Catégories** du back-office.

**Étape 3 — Votre compte.** Choisissez un identifiant et un mot de passe d'au
moins 12 caractères, mélangeant lettres, chiffres et symboles. Le mot de passe
n'est jamais enregistré tel quel, seulement son empreinte.

---

## 5. Supprimer l'installateur — obligatoire

À la fin, la page affiche un bouton **« Supprimer setup.php et ouvrir le
back-office »**. Cliquez dessus.

S'il échoue (droits insuffisants), supprimez le fichier vous-même :
dans FileZilla, `admin/setup.php` → clic droit → **Supprimer**.

> **Pourquoi c'est important.** Tant qu'aucun compte n'existe, cette page peut
> créer le premier. Elle se verrouille d'elle-même dès qu'un compte est là —
> mais un fichier d'installation qui traîne reste un fichier de trop. C'est
> exactement le genre d'oubli qui a ouvert la porte à l'ancien piratage.

---

## 6. Vérifier que tout fonctionne

| À tester | Résultat attendu |
|---|---|
| `https://alamstores.ma` | la page d'accueil, carrousel qui défile |
| `https://alamstores.ma/pergolas` | la page s'ouvre (URL sans extension → le `.htaccess` fonctionne) |
| `https://alamstores.ma/pergolas.html` | redirige (301) vers `/pergolas` — les anciennes adresses restent valables |
| La bulle verte en bas à droite | ouvre WhatsApp sur le 06 00 05 55 62 |
| `https://alamstores.ma/admin/` | l'écran de connexion, puis le tableau de bord |
| Ajouter un produit de test | il apparaît sur la page de sa catégorie |
| Sur `/devis`, la case reCAPTCHA | elle s'affiche au-dessus du bouton d'envoi |
| Envoyer un devis de test depuis `/devis` | WhatsApp s'ouvre avec la demande, elle apparaît dans **Devis reçus**, et un e-mail arrive |
| Envoyer le formulaire à moitié vide | il revient avec vos réponses conservées et les champs en erreur signalés |
| Les deux boutons d'envoi | **Envoyer sur WhatsApp** ouvre la conversation pré-remplie, **Envoyer par e-mail** affiche un accusé de réception |
| Dans **Catégories**, masquer une catégorie | elle disparaît du menu et sa page renvoie 404 ; la réafficher la remet |
| `https://alamstores.ma/api/config.php` | **doit afficher une erreur 403** |
| `https://alamstores.ma/db/schema.sql` | **doit afficher une erreur 404** |

Les deux derniers sont les plus importants : s'ils affichent du contenu, votre
hébergeur n'applique pas les `.htaccess`. Contactez son support en demandant
**« AllowOverride All »** sur votre dossier — n'utilisez pas le site avant.

Si l'e-mail de devis n'arrive pas, **regardez d'abord les indésirables**.
Beaucoup d'hébergements envoient depuis une adresse que Gmail connaît mal.
Demandez à votre hébergeur d'ajouter un enregistrement **SPF** à votre domaine.
Cela ne bloque jamais l'enregistrement de la demande : elle est en base de
toute façon, visible dans **Devis reçus**.

---

## 7. Sécuriser

1. **Forcez le HTTPS.** Activez le certificat SSL gratuit (Let's Encrypt) dans
   le panneau, puis ajoutez tout en haut du `.htaccess` :

   ```apache
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]
   ```

2. **Supprimez l'ancien site WordPress** du serveur, une fois le nouveau
   vérifié. Il est infecté : voir `SECURITY-AUDIT.md` §1.

3. **Changez le mot de passe MySQL de l'ancienne base.** Il a été publié en
   clair dans l'ancien dépôt (`SECURITY-AUDIT.md` §3).

4. **Lancez le scanner**, si votre hébergeur donne accès à SSH ou à un
   planificateur de tâches :

   ```bash
   cd ~/public_html
   python3 tools/security_scan.py --baseline   # une fois, juste après l'installation
   python3 tools/security_scan.py              # ensuite, régulièrement
   ```

   En tâche planifiée hebdomadaire — il ne parle qu'en cas de problème :

   ```
   0 4 * * 1 cd ~/public_html && python3 tools/security_scan.py --quiet
   ```

5. **Sauvegardez.** Une base exportée depuis phpMyAdmin plus une copie de
   `assets/uploads/` suffisent : tout le reste est dans le dépôt Git.

---

## 8. Utiliser le back-office au quotidien

**Ajouter un produit** — `/admin/` → **Nouveau produit**. Obligatoires : titre,
catégorie, description et au moins une photo. Facultatifs : lien YouTube et
fiches techniques PDF. Enregistrez : le produit apparaît immédiatement sur la
page de sa catégorie.

**Ajouter une déclinaison** — même formulaire, en choisissant le produit
principal dans **Sous-produit de**. Elle s'affiche en retrait sous son parent,
dans un bloc **Déclinaisons**.

**Gérer les catégories** — onglet **Catégories**. Les deux familles, Intérieur
et Extérieur, avec leurs sous-catégories sur trois niveaux. Sur chaque ligne :
▲▼ pour l'ordre, l'œil pour afficher ou masquer, **+** pour ajouter une
sous-catégorie, le crayon pour renommer, la croix pour supprimer. Tout est
répercuté sur le site à la page suivante, sans regénération.

> Masquer plutôt que supprimer : une catégorie masquée sort du menu et sa page
> renvoie 404, mais ses produits sont conservés. La suppression, elle, est
> définitive et emporte les sous-catégories et les produits — le back-office
> demande une seconde confirmation dans ce cas.
>
> Renommer ne change jamais l'adresse de la page : les liens déjà partagés et
> indexés continuent de fonctionner.

**Masquer sans supprimer** — passez **Visibilité** sur *Brouillon*.

**Lire les demandes de devis** — onglet **Devis reçus**. Recherche, filtres par
statut et par catégorie, marquage lu / non lu, et **Exporter en CSV** pour
ouvrir la liste dans Excel (les accents passent correctement).

**Modifier autre chose** (textes des pages, coordonnées, logos partenaires) :
ce sont des fichiers, pas la base. Voir la section « Rebuilding » du `README.md`.

---

## 9. En cas de problème

| Symptôme | Cause la plus fréquente | Solution |
|---|---|---|
| Page blanche sur `/admin/` | PHP trop ancien | Passez PHP à 8.1+ dans le panneau |
| « Configuration absente » | `api/config.php` manquant | Relancez `admin/setup.php` |
| Erreur 500 sur tout le site | `.htaccess` non appliqué ou `mod_rewrite` absent | Demandez `AllowOverride All` au support |
| `/pergolas` en 404 mais `/pergolas.html` fonctionne | `mod_rewrite` désactivé | Idem |
| « Connexion refusée » à l'étape 1 | serveur `localhost` incorrect | Essayez l'adresse exacte donnée par l'hébergeur |
| Photos qui ne se téléversent pas | limite `upload_max_filesize` | Passez-la à 16 Mo dans le panneau PHP |
| Le site s'affiche sans mise en forme | `assets/` incomplet | Retransférez le dossier avec FileZilla |
| Les produits ne s'affichent pas | la base ne répond pas | Ouvrez `/api/catalog.php` : vous devez voir du JSON |
| La case reCAPTCHA ne s'affiche pas | vos clés sont en v3, pas en v2 | Passez `'version' => 'v3'` dans `api/config.php` |
| « La vérification anti-robot n'a pas abouti » à chaque envoi | clé secrète erronée, ou clé v3 déclarée en v2 | Vérifiez les deux clés sur google.com/recaptcha/admin |

> **Le site ne casse jamais parce que la base est en panne.** Le catalogue est
> écrit par le serveur depuis la base ; si elle ne répond pas, chaque page
> retombe sur la copie figée au moment de la génération. Et tant que
> `api/config.php` n'existe pas — l'état juste après le transfert FTP — toutes
> les pages s'affichent quand même, entièrement.
>
> Testé : base injoignable et fichier de configuration absent renvoient tous
> deux des pages complètes en HTTP 200.

---

## Ce qu'il vous reste à décider

- **Le texte des pages.** Il a été réécrit pour cette refonte : l'original était
  dans la base WordPress, qui n'était pas dans l'export. Relisez
  `tools/content_fr.py` avant de communiquer dessus.
- **Les logos partenaires** de la page Partenaires viennent de votre ancienne
  médiathèque. Vérifiez que vous avez toujours le droit de les afficher.
- **La page Motorisations** existe toujours à son adresse et reste dans le
  `sitemap.xml`, mais plus aucun lien du site n'y mène. Dites-le-moi si vous
  préférez la remettre au menu ou la supprimer complètement.
