<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin - Info_Varotra' ?></title>
    <link rel="stylesheet" href="/Info_Varotra/assets/css/style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="/Info_Varotra/admin/index.php" class="logo">Admin_<span>Varotra</span></a>

        <!-- Bouton Hamburger (Trois barres horizontales) -->
        <button class="menu-toggle" id="admin-menu-toggle" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Menu de navigation Administration -->
        <ul class="nav-links" id="admin-nav-links">
            <li><a href="/Info_Varotra/admin/index.php">Tableau de bord</a></li>
            <li><a href="/Info_Varotra/admin/categories.php">Catégories</a></li>
            <li><a href="/Info_Varotra/admin/produits.php">Produits</a></li>
            <li><a href="/Info_Varotra/admin/services.php">Services</a></li>
            <li><a href="/Info_Varotra/admin/commandes.php">Commandes</a></li>
            <li><a href="/Info_Varotra/admin/demandes.php">Rendez-vous</a></li>
            <li><a href="/Info_Varotra/admin/clients.php">Clients</a></li>
             <li><a href="/Info_Varotra/admin/deconnexion.php" class="btn-primary" style="background-color: #dc2626;">Déconnexion</a></li>
        </ul>
    </nav>
</header>
