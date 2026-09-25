-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 25 sep. 2026 à 16:17
-- Version du serveur : 8.0.46-0ubuntu0.24.04.3
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Info_Varotra`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_admin` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('super_admin','gestionnaire') DEFAULT 'gestionnaire'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`id_admin`, `nom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Admin Principal', 'admin@infovarotra.local', '$2y$10$.W3KtP2uzEmpvAVgzy5zRegMLnELj9qmm3Y4y3SIjxRq2PfOfQdfS', 'super_admin');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int NOT NULL,
  `nom_categorie` varchar(100) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`, `description`) VALUES
(1, 'Ordinateurs Portables', 'Laptops pour le travail, le gaming et le traitement multimédia.'),
(2, 'Composants PC', 'Processeurs, cartes graphiques, barrettes de RAM et cartes mères.'),
(3, 'Périphériques', 'Claviers, souris, casques audio et écrans d\'ordinateur.'),
(4, 'Stockage & Réseaux', 'Disques durs externes, SSD, clés USB, routeurs et câbles réseau.'),
(5, 'Smartphones & Tablettes', 'Téléphones portables, tablettes tactiles et accessoires mobiles.');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` text,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `actif` tinyint(1) DEFAULT '0',
  `token_verification` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom`, `prenom`, `email`, `mot_de_passe`, `telephone`, `adresse`, `date_inscription`, `actif`, `token_verification`) VALUES
(1, 'ANDRIANARIJAONA', 'Henintsoa Faneva', 'fanevaah0501@gmail.com', '$2y$10$8VITDCkvaEKQpyMfH9w0KeHekxb2BAud0MLS.0pPvLTpWJID.OchO', '0381563729', 'Antananarivo', '2026-09-23 16:07:50', 0, NULL),
(2, 'ANDRIANARIJAONA', 'Irintsoa Fanasina', 'fanasina0502@gmail.com', '$2y$10$sXEBPI4pOx60ZkfdiiKK9Ov46A/36Hoy31/uyRYvqqa6sIeC210gS', '0331436697', 'Antananarivo', '2026-09-23 19:46:05', 0, NULL),
(3, 'ANDRIANARIJAONA', 'Harentsoa Faniry', 'harentsofaniry@gmail.com', '$2y$10$YPV/pSzGvB1sCUxZKthsluyfi34rozi7K769DxZthwNrq8anpIGzW', '0340981216', 'ANTANANARIVO', '2026-09-24 15:18:24', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int NOT NULL,
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en_attente','validee','expediee','livree','annulee') DEFAULT 'en_attente',
  `montant_total` decimal(10,2) NOT NULL,
  `id_client` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `date_commande`, `statut`, `montant_total`, `id_client`) VALUES
(2, '2026-09-25 10:10:55', 'en_attente', 100000.00, 2);

-- --------------------------------------------------------

--
-- Structure de la table `demande_service`
--

CREATE TABLE `demande_service` (
  `id_demande` int NOT NULL,
  `date_demande` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_rdv` datetime DEFAULT NULL,
  `statut` enum('en_attente','confirmee','terminee','annulee') DEFAULT 'en_attente',
  `description_probleme` text,
  `id_client` int NOT NULL,
  `id_service` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligne_commande`
--

CREATE TABLE `ligne_commande` (
  `id_commande` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ligne_commande`
--

INSERT INTO `ligne_commande` (`id_commande`, `id_produit`, `quantite`, `prix_unitaire`) VALUES
(2, 10, 1, 100000.00);

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` int NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `mode_paiement` enum('carte_bancaire','mobile_money','especes','virement') NOT NULL,
  `date_paiement` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en_attente','complete','echoue','rembourse') DEFAULT 'en_attente',
  `id_commande` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int NOT NULL,
  `nom_produit` varchar(150) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `id_categorie` int NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom_produit`, `description`, `prix`, `stock`, `image`, `id_categorie`, `image_url`) VALUES
(1, 'PC Portable Asus Vivobook 15', 'Écran 15.6 Full HD, Intel Core i5 11ème Gen, 16 Go RAM, 512 Go SSD, Windows 11.', 2400000.00, 10, 'asus_vivobook.jpg', 1, NULL),
(2, 'MacBook Air M2 13\"', 'Puce Apple M2, 8 Go RAM Unified, 256 Go SSD, Écran Liquid Retina, Coloris Gris Sidéral.', 5200000.00, 5, 'macbook_m2.jpg', 1, NULL),
(3, 'Processeur AMD Ryzen 5 5600X', '6 cœurs / 12 threads, Fréquence 3.7 GHz (Boost 4.6 GHz), Socket AM4.', 850000.00, 15, 'ryzen_5600x.jpg', 2, NULL),
(4, 'Carte Graphique RTX 4060 8GB', 'NVIDIA GeForce RTX 4060, 8 Go GDDR6, Ray Tracing, DLSS 3, PCI Express 4.0.', 1800000.00, 8, 'rtx_4060.jpg', 2, NULL),
(5, 'Souris Logitech MX Master 3S', 'Souris sans fil ergonomique, capteur 8000 DPI, clics silencieux, connectivité Bluetooth/Unifying.', 450000.00, 20, 'logitech_mx3s.jpg', 3, 'assets/images/boutton.png'),
(6, 'Clavier Mécanique RGB Redragon K552', 'Clavier mécanique Gamer, Switchs Bleus, rétroéclairage RGB personnalisable, AZERTY.', 220000.00, 25, 'redragon_k552.jpg', 3, NULL),
(7, 'Disque Dur Externe WD 1 To', 'Format 2.5 pouces, USB 3.0, sauvegarde automatique, compatible Windows et Mac.', 280000.00, 30, 'wd_1tb.jpg', 4, 'assets/images/quitter.png'),
(8, 'SSD NVMe Samsung 980 1 To', 'Format M.2 NVMe PCIe 3.0, vitesse de lecture jusqu\'à 3500 Mo/s.', 420000.00, 18, 'samsung_980.jpg', 4, NULL),
(9, 'Samsung Galaxy A34 5G', 'Écran Super AMOLED 120Hz, 128 Go stockage, 6 Go RAM, Triple appareil photo 48 MP.', 1350000.00, 12, 'galaxy_a34.jpg', 5, ''),
(10, 'Chargeur de PC portable', 'Sous carton et en bon état.', 100000.00, 14, NULL, 3, ''),
(12, 'Housse d\'Ordinateurs Portables', 'Protection assurée lors des chocs.', 25000.00, 24, NULL, 3, 'assets/images/boutton.png'),
(13, 'Casque audio', 'Son Haute qualité', 40000.00, 1, NULL, 3, 'assets/images/boutton.png'),
(14, 'Bouttons', 'Qualité haut de gamme, et touche tès fluide', 1500.00, 12, NULL, 2, 'assets/images/boutton.png');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id_service` int NOT NULL,
  `nom_service` varchar(150) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `duree_estimee` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id_service`, `nom_service`, `description`, `prix`, `duree_estimee`) VALUES
(1, 'Maintenance & Nettoyage PC', 'Dépoussiérage, changement de pâte thermique, optimisation système', 30000.00, '2 heures'),
(2, 'Installation Système / Formatage', 'Installation Windows 11/Linux avec pilotes et logiciels de base', 40000.00, '3 heures'),
(3, 'Récupération de données', 'Diagnostic et extraction de données sur disque dur défectueux', 75000.00, '24 à 48 heures'),
(4, 'Formattage de clé USB', 'Fini en un rien de temps qu\'il ne faut pour le dire.', 5000.00, '30 minutes'),
(5, 'Collage d\'écran', 'Client satisfait ou remboursé.', 15000.00, '4 heures');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `demande_service`
--
ALTER TABLE `demande_service`
  ADD PRIMARY KEY (`id_demande`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_service` (`id_service`);

--
-- Index pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD PRIMARY KEY (`id_commande`,`id_produit`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_commande` (`id_commande`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `demande_service`
--
ALTER TABLE `demande_service`
  MODIFY `id_demande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE CASCADE;

--
-- Contraintes pour la table `demande_service`
--
ALTER TABLE `demande_service`
  ADD CONSTRAINT `demande_service_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE CASCADE,
  ADD CONSTRAINT `demande_service_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD CONSTRAINT `ligne_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
