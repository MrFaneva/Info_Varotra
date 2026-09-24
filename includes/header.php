<?php
// Fichier : includes/header.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calcul du nombre d'articles dans le panier
$panier_count = isset($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info_Varotra - Vente Matériels & Services Informatiques</title>
    <link rel="stylesheet" href="/Info_Varotra/assets/css/style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="/Info_Varotra/index.php" class="logo">Info_<span>Varotra</span></a>
        
        <ul class="nav-links">
            <li><a href="/Info_Varotra/index.php">Accueil</a></li>
            <li><a href="/Info_Varotra/index.php#produits">Produits</a></li>
            <li><a href="/Info_Varotra/index.php#services">Services</a></li>
            <li>
                <a href="/Info_Varotra/client/panier.php">
                    🛒 Panier (<?= $panier_count ?>)
                </a>
            </li>

            <?php if (isset($_SESSION['client_id'])): ?>
                <li><a href="/Info_Varotra/client/espace.php">Mon Compte</a></li>
                <li><a href="/Info_Varotra/client/deconnexion.php">Déconnexion</a></li>
            <?php elseif (isset($_SESSION['admin_id'])): ?>
                <li><a href="/Info_Varotra/admin/index.php" class="btn-primary">Espace Admin</a></li>
            <?php else: ?>
                <li><a href="/Info_Varotra/client/connexion.php" class="btn-primary">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
