-- Fichier : database/site_informatique.sql

CREATE DATABASE IF NOT EXISTS info_varotra CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE info_varotra;

-- Désactivation temporaire de la vérification des clés étrangères pour la réinitialisation
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS paiement;
DROP TABLE IF EXISTS demande_service;
DROP TABLE IF EXISTS ligne_commande;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS service;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS client;
DROP TABLE IF EXISTS administrateur;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. Table CLIENT
CREATE TABLE client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse TEXT,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Table CATEGORIE
CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- 3. Table PRODUIT
CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom_produit VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    id_categorie INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Table COMMANDE
CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'validee', 'expediee', 'livree', 'annulee') DEFAULT 'en_attente',
    montant_total DECIMAL(10,2) NOT NULL,
    id_client INT NOT NULL,
    FOREIGN KEY (id_client) REFERENCES client(id_client) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Table LIGNE_COMMANDE
CREATE TABLE ligne_commande (
    id_commande INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_commande, id_produit),
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Table SERVICE
CREATE TABLE service (
    id_service INT AUTO_INCREMENT PRIMARY KEY,
    nom_service VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    duree_estimee VARCHAR(50)
) ENGINE=InnoDB;

-- 7. Table DEMANDE_SERVICE
CREATE TABLE demande_service (
    id_demande INT AUTO_INCREMENT PRIMARY KEY,
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_rdv DATETIME,
    statut ENUM('en_attente', 'confirmee', 'terminee', 'annulee') DEFAULT 'en_attente',
    description_probleme TEXT,
    id_client INT NOT NULL,
    id_service INT NOT NULL,
    FOREIGN KEY (id_client) REFERENCES client(id_client) ON DELETE CASCADE,
    FOREIGN KEY (id_service) REFERENCES service(id_service) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Table PAIEMENT
CREATE TABLE paiement (
    id_paiement INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(10,2) NOT NULL,
    mode_paiement ENUM('carte_bancaire', 'mobile_money', 'especes', 'virement') NOT NULL,
    date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'complete', 'echoue', 'rembourse') DEFAULT 'en_attente',
    id_commande INT NOT NULL,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. Table ADMINISTRATEUR
CREATE TABLE administrateur (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'gestionnaire') DEFAULT 'gestionnaire'
) ENGINE=InnoDB;
