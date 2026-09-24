<?php
// Fichier : admin/index.php

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification des droits d'accès admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/login.php');
    exit();
}

// Récupération de quelques statistiques pour le tableau de bord
$total_clients = $pdo->query("SELECT COUNT(*) FROM client")->fetchColumn();
$total_produits = $pdo->query("SELECT COUNT(*) FROM produit")->fetchColumn();
$total_commandes = $pdo->query("SELECT COUNT(*) FROM commande")->fetchColumn();
$total_services = $pdo->query("SELECT COUNT(*) FROM demande_service")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categorie")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Administration Info_Varotra</title>
    <link rel="stylesheet" href="/Info_Varotra/assets/css/style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="/Info_Varotra/admin/index.php" class="logo">Admin_<span>Varotra</span></a>
        <ul class="nav-links">
            <li><a href="/Info_Varotra/admin/index.php">Tableau de bord</a></li>
            <li><a href="/Info_Varotra/index.php" target="_blank">Voir le site public ↗</a></li>
            <li><a href="/Info_Varotra/admin/categories.php">Catégories</a></li>
            <li><a href="/Info_Varotra/admin/produits.php">Produits</a></li>
            <li><a href="/Info_Varotra/admin/commandes.php">Commandes</a></li>
            <li><a href="/Info_Varotra/admin/demandes.php">Rendez-vous</a></li>
            <li><a href="/Info_Varotra/admin/clients.php">Clients</a></li>
            <li><a href="/Info_Varotra/admin/deconnexion.php" class="btn-primary" style="background-color: #dc2626;">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main class="container">
    <div style="margin-bottom: 2rem;">
        <h1>Tableau de bord</h1>
        <p style="color: #64748b;">Bienvenue, <strong><?= htmlspecialchars($_SESSION['admin_nom']) ?></strong> (<?= htmlspecialchars($_SESSION['admin_role']) ?>)</p>
    </div>

    <!-- Grille de statistiques -->
    <div class="grid" style="margin-bottom: 3rem;">
        <a href="/Info_Varotra/admin/produits.php" style="text-decoration: none; color: inherit;">
            <div class="card" style="border-left: 4px solid #2563eb;">
                <h3>Produits en catalogue</h3>
                <p style="font-size: 2rem; font-weight: bold; color: #2563eb; margin: 0.5rem 0;"><?= $total_produits ?></p>
            </div>
        </a>
        
        <a href="/Info_Varotra/admin/commandes.php" style="text-decoration: none; color: inherit;">
            <div class="card" style="border-left: 4px solid #16a34a;">
                <h3>Commandes passées</h3>
                <p style="font-size: 2rem; font-weight: bold; color: #16a34a; margin: 0.5rem 0;"><?= $total_commandes ?></p>
            </div>
        </a>
        
        <a href="/Info_Varotra/admin/demandes.php" style="text-decoration: none; color: inherit;">
            <div class="card" style="border-left: 4px solid #d97706;">
                <h3>Demandes de services</h3>
                <p style="font-size: 2rem; font-weight: bold; color: #d97706; margin: 0.5rem 0;"><?= $total_services ?></p>
            </div>
        </a>
        
        <a href="/Info_Varotra/admin/clients.php" style="text-decoration: none; color: inherit;">
            <div class="card" style="border-left: 4px solid #9333ea;">
                <h3>Clients inscrits</h3>
                <p style="font-size: 2rem; font-weight: bold; color: #9333ea; margin: 0.5rem 0;"><?= $total_clients ?></p>
            </div>
        </a>
        
        <a href="/Info_Varotra/admin/categories.php" style="text-decoration: none; color: inherit;">
            <div class="card" style="border-left: 4px solid #0f172a;">
                <h3>Catégories</h3>
                <p style="font-size: 2rem; font-weight: bold; color: #0f172a; margin: 0.5rem 0;"><?= $total_categories ?></p>
            </div>
        </a>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
