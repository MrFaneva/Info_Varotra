<?php
// Fichier : admin/categories.php

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/login.php');
    exit();
}

$message = '';
$error = '';

// Ajout / Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_categorie = trim($_POST['nom_categorie'] ?? '');
    $id_cat = (int)($_POST['id_categorie'] ?? 0);

    if (!empty($nom_categorie)) {
        if ($id_cat > 0) {
            $stmt = $pdo->prepare("UPDATE categorie SET nom_categorie = :nom WHERE id_categorie = :id");
            $stmt->execute(['nom' => $nom_categorie, 'id' => $id_cat]);
            $message = "Catégorie mise à jour avec succès.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO categorie (nom_categorie) VALUES (:nom)");
            $stmt->execute(['nom' => $nom_categorie]);
            $message = "Nouvelle catégorie ajoutée.";
        }
    } else {
        $error = "Le nom de la catégorie ne peut pas être vide.";
    }
}

// Suppression
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_del = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM categorie WHERE id_categorie = :id");
        $stmt->execute(['id' => $id_del]);
        header('Location: categories.php');
        exit();
    } catch (PDOException $e) {
        $error = "Impossible de supprimer cette catégorie car des produits y sont associés.";
    }
}

// Récupération pour modification
$cat_edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM categorie WHERE id_categorie = :id");
    $stmt->execute(['id' => (int)$_GET['id']]);
    $cat_edit = $stmt->fetch();
}

$categories = $pdo->query("SELECT * FROM categorie ORDER BY nom_categorie ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Catégories - Admin</title>
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
            <li><a href="/Info_Varotra/admin/demandes.php">Rendez-vous</a></li>
            <li><a href="/Info_Varotra/admin/deconnexion.php" style="color: #ef4444;">Déconnexion</a></li>
        </ul>
    </nav>
</header>

<main class="container">
    <h2>Gestion des Catégories</h2>

    <?php if ($message): ?><div class="alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-top: 1.5rem;">
        <!-- Formulaire Ajout / Édition -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color);">
            <h3><?= $cat_edit ? 'Modifier la catégorie' : 'Ajouter une catégorie' ?></h3>
            <form action="categories.php" method="POST" style="margin-top: 1rem;">
                <input type="hidden" name="id_categorie" value="<?= $cat_edit['id_categorie'] ?? '' ?>">
                <div class="form-group">
                    <label for="nom_categorie">Nom de la catégorie *</label>
                    <input type="text" id="nom_categorie" name="nom_categorie" value="<?= htmlspecialchars($cat_edit['nom_categorie'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; border: none; padding: 0.7rem; cursor: pointer;">
                    <?= $cat_edit ? 'Mettre à jour' : 'Enregistrer' ?>
                </button>
                <?php if ($cat_edit): ?>
                    <a href="categories.php" style="display: block; text-align: center; margin-top: 0.5rem; color: #64748b; text-decoration: none;">Annuler</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Liste des catégories -->
        <div>
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
                <thead>
                    <tr style="background: var(--primary); color: white; text-align: left;">
                        <th style="padding: 0.8rem;">ID</th>
                        <th style="padding: 0.8rem;">Nom</th>
                        <th style="padding: 0.8rem; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.8rem;">#<?= $cat['id_categorie'] ?></td>
                            <td style="padding: 0.8rem; font-weight: 600;"><?= htmlspecialchars($cat['nom_categorie']) ?></td>
                            <td style="padding: 0.8rem; text-align: center;">
                                <a href="categories.php?action=edit&id=<?= $cat['id_categorie'] ?>" style="color: #2563eb; text-decoration: none; margin-right: 0.5rem;">Éditer</a>
                                <a href="categories.php?action=delete&id=<?= $cat['id_categorie'] ?>" onclick="return confirm('Supprimer cette catégorie ?');" style="color: #dc2626; text-decoration: none;">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
