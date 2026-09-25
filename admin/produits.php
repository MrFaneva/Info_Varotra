<?php
// Fichier : admin/produits.php

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/login.php');
    exit();
}

// Suppression d'un produit
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id");
    $stmt->execute(['id' => (int)$_GET['id']]);
    header('Location: produits.php');
    exit();
}

$produits = $pdo->query("SELECT p.*, c.nom_categorie 
                        FROM produit p 
                        LEFT JOIN categorie c ON p.id_categorie = c.id_categorie 
                        ORDER BY p.id_produit DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Produits - Admin</title>
    <link rel="stylesheet" href="/Info_Varotra/assets/css/style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="/Info_Varotra/admin/index.php" class="logo">Admin_<span>Varotra</span></a>
        <ul class="nav-links">
            <li><a href="/Info_Varotra/admin/index.php">Tableau de bord</a></li>
            <li><a href="/Info_Varotra/admin/categories.php">Catégories</a></li>
            <li><a href="/Info_Varotra/admin/produits.php">Produits</a></li>
            <li><a href="/Info_Varotra/admin/commandes.php">Commandes</a></li>
            <li><a href="/Info_Varotra/admin/services.php">Services</a></li>
            <li><a href="/Info_Varotra/admin/demandes.php">Rendez-vous</a></li>
            <li><a href="/Info_Varotra/admin/deconnexion.php" style="color: #ef4444;">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Gestion du Catalogue Produits</h2>
        <a href="produit_form.php" class="btn-primary" style="text-decoration: none;">+ Ajouter un produit</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
        <thead>
            <tr style="background: var(--primary); color: white; text-align: left;">
                <th style="padding: 0.8rem;">ID</th>
                <th style="padding: 0.8rem;">Nom</th>
                <th style="padding: 0.8rem;">Catégorie</th>
                <th style="padding: 0.8rem;">Prix</th>
                <th style="padding: 0.8rem;">Stock</th>
                <th style="padding: 0.8rem; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $prod): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 0.8rem;">#<?= $prod['id_produit'] ?></td>
                    <td style="padding: 0.8rem; font-weight: bold;"><?= htmlspecialchars($prod['nom_produit']) ?></td>
                    <td style="padding: 0.8rem;"><?= htmlspecialchars($prod['nom_categorie'] ?? 'Non catégorisé') ?></td>
                    <td style="padding: 0.8rem; color: var(--accent); font-weight: bold;"><?= number_format($prod['prix'], 2, ',', ' ') ?> Ar</td>
                    <td style="padding: 0.8rem;"><?= $prod['stock'] ?></td>
                    <td style="padding: 0.8rem; text-align: center;">
                        <a href="produit_form.php?id=<?= $prod['id_produit'] ?>" style="color: #2563eb; text-decoration: none; margin-right: 0.5rem;">Éditer</a>
                        <a href="produits.php?action=delete&id=<?= $prod['id_produit'] ?>" onclick="return confirm('Supprimer ce produit ?');" style="color: #dc2626; text-decoration: none;">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/includes/footer.php';?>
