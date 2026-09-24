# 🛒 Info_Varotra - Plateforme E-Commerce & Services Informatiques

**Info_Varotra** est une application web dynamique développée en **PHP natif** et **MySQL** (sans aucun framework ni bibliothèque externe). Elle associe une boutique en ligne de matériel informatique à un service de réservation de prestations informatiques. L'application comprend un espace client sécurisé ainsi qu'un Back-Office administrateur complet.

---

## 🚀 Fonctionnalités Principales

### 🛍️ Client / Front-Office
* **Catalogue Dynamique :** Consultation des produits par catégories, fiche détaillée et gestion des stocks.
* **Gestion du Panier & Commandes :** Ajout/suppression d'articles, calcul du total et validation des achats.
* **Prise de Rendez-vous (Services) :** Formulaire de réservation de prestations informatiques avec contrôle d'horaires.
* **Authentification Sécurisée :** Inscription directe avec vérification d'unicité de l'email et hachage fort des mots de passe (`password_hash` BCRYPT).
* **Espace Client :** Suivi des commandes, état des demandes de services et historique du compte.

### 🛠️ Administration / Back-Office
* **Tableau de Bord :** Vue d'ensemble des métriques clés (nombre de produits, commandes, rendez-vous, catégories et clients inscrits).
* **Gestion du Catalogue :** CRUD complet des produits et catégories (Ajout, modification, suppression, gestion des stocks et images).
* **Gestion des Commandes :** Traitement et suivi de l'état des commandes.
* **Gestion des Rendez-vous :** Validation et suivi des prestations informatiques sollicitées.
* **Gestion des Clients :** Consultation de la liste des membres inscrits.

---

## 🛠️ Stack Technique

* **Langage Back-End :** PHP Natif 100 % (Architecture modulaire, requêtes préparées avec `PDO`).
* **Base de Données :** MySQL / MariaDB (Gestion des clés étrangères et contraintes d'intégrité).
* **Front-End :** HTML5, CSS3, JavaScript.
* **Dépendances Externes :** Aucune (*Zero Dependency*).

---

## 📋 Prérequis

* **Environnement Web Local :** XAMPP / WAMP / MAMP (PHP 7.4+ ou PHP 8.x).
* **Extension PHP :** `pdo_mysql`.
* **Serveur MySQL / phpMyAdmin**.

---

## 📦 Procédure d'Installation en Local

### 1. Cloner le Dépôt
Placez-vous dans votre dossier Web local (`xampp/htdocs/` ou `wamp64/www/`) puis exécutez :
```bash
git clone [https://github.com/MrFaneva/Info_Varotra.git](https://github.com/MrFaneva/Info_Varotra.git) Info_Varotra


### 2. Importer la Base de Données
1. Ouvrez phpMyAdmin (`http://localhost/phpmyadmin`).
2. Créez une nouvelle base de données nommée **`Info_Varotra`** (Interclassement : `utf8mb4_general_ci`).
3. Importez le fichier SQL présent à la racine ou dans le dossier `database/` de votre projet (`info_varotra.sql`).

### 3. Configuration (`config/database.php`)
Ouvrez le fichier `config/database.php` et adaptez si nécessaire vos identifiants de connexion MySQL locaux :

```php
$host = '127.0.0.1';
$dbname = 'Info_Varotra';
$username = 'root';
$password = ''; // Indiquez votre mot de passe MySQL local s'il existe

🔑 Accès au Back-Office

    URL d'accès : http://localhost/Info_Varotra/admin/login.php ou bien via le footer il y a "Espace Admin"

    Identifiants Admin par défaut :

        Email / Login : admin@infovarotra.local

        Mot de passe : Admin123! (ou les identifiants définis dans la table admin)

📂 Arborescence du Projet:
```text
    Info_Varotra/
    ├── admin/                # Back-office (dashboard, CRUD catégories, produits, commandes, clients)
    ├── assets/               # Fichiers CSS, JS et images
    ├── client/               # Espace client (inscription.php, connexion.php, panier, RDV)
    ├── config/               # Connexion PDO à la BDD (database.php)
    ├── database/             # Script d'importation de la BDD (.sql)
    ├── includes/             # Composants d'interface réutilisables (header.php, footer.php)
    ├── index.php             # Page d'accueil du site
    └── README.md             # Documentation du projet

👤 Auteur & Contact

Projet développé par Faneva.

N'hésitez pas à me contacter pour toute question ou remarque concernant l'architecture ou le déploiement du projet.

📄 Licence

Ce projet est réalisé dans un cadre d'évaluation. Tous droits réservés.
