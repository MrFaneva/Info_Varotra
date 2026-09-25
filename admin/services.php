<?php
// Fichier : admin/services.php

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/login.php');
    exit();
}

// Suppression d'un service
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM service WHERE id_service = :id");
        $stmt->execute(['id' => (int)$_GET['id']]);
        header('Location: services.php');
        exit();
    } catch (PDOException $e) {
        $error = "Impossible de supprimer ce service car des demandes y sont liées.";
    }
}

$services = $pdo->query("SELECT * FROM service ORDER BY id_service DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Services - Admin</title>
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
            <li><a href="/Info_Varotra/admin/services.php" style="font-weight: bold; color: var(--accent);">Services</a></li>
            <li><a href="/Info_Varotra/admin/commandes.php">Commandes</a></li>
            <li><a href="/Info_Varotra/admin/demandes.php">Rendez-vous</a></li>
            <li><a href="/Info_Varotra/admin/clients.php">Clients</a></li>
            <li><a href="/Info_Varotra/admin/deconnexion.php" style="color: #ef4444;">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Gestion des Services Informatiques</h2>
        <a href="service_form.php" class="btn-primary" style="text-decoration: none;">+ Ajouter un service</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert-error" style="margin-bottom: 1rem;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
        <thead>
            <tr style="background: var(--primary); color: white; text-align: left;">
                <th style="padding: 0.8rem;">ID</th>
                <th style="padding: 0.8rem;">Nom du service</th>
                <th style="padding: 0.8rem;">Durée</th>
                <th style="padding: 0.8rem;">Prix</th>
                <th style="padding: 0.8rem; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $srv): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 0.8rem;">#<?= $srv['id_service'] ?></td>
                    <td style="padding: 0.8rem; font-weight: bold;"><?= htmlspecialchars($srv['nom_service']) ?></td>
                    <td style="padding: 0.8rem;"><?= htmlspecialchars($srv['duree_estimee'] ?? 'Non spécifiée') ?></td>
                    <td style="padding: 0.8rem; color: var(--accent); font-weight: bold;"><?= number_format($srv['prix'], 2, ',', ' ') ?> Ar</td>
                    <td style="padding: 0.8rem; text-align: center;">
                        <a href="service_form.php?id=<?= $srv['id_service'] ?>" style="color: #2563eb; text-decoration: none; margin-right: 0.5rem;">Éditer</a>
                        <a href="services.php?action=delete&id=<?= $srv['id_service'] ?>" onclick="return confirm('Supprimer ce service ?');" style="color: #dc2626; text-decoration: none;">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/includes/footer.php';?>
