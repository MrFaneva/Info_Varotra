<?php
// Fichier : admin/produit_form.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/login.php');
    exit();
}

$id_produit = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produit = [
    'nom_produit' => '',
    'description' => '',
    'prix' => '',
    'stock' => '',
    'id_categorie' => '',
    'image_url' => ''
];
$error = '';

if ($id_produit > 0) {
    $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id");
    $stmt->execute(['id' => $id_produit]);
    $p = $stmt->fetch();
    if ($p) $produit = $p;
}

$categories = $pdo->query("SELECT * FROM categorie ORDER BY nom_categorie ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom_produit'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $prix = (float)($_POST['prix'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $id_cat = (int)($_POST['id_categorie'] ?? 0);
    $image = trim($_POST['image_url'] ?? '');

    // AJOUT : Vérification du stock ($stock <= 0)
    if (empty($nom) || $prix <= 0 || $id_cat <= 0 || $stock <= 0) {
        $error = "Veuillez renseigner un nom, un prix valide, une catégorie et une quantité en stock supérieure à zéro.";
    } else {
        if ($id_produit > 0) {
            $stmt = $pdo->prepare("UPDATE produit SET nom_produit = :nom, description = :desc, prix = :prix, stock = :stock, id_categorie = :cat, image_url = :img WHERE id_produit = :id");
            $stmt->execute([
                'nom' => $nom, 'desc' => $desc, 'prix' => $prix, 'stock' => $stock, 'cat' => $id_cat, 'img' => $image, 'id' => $id_produit
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO produit (nom_produit, description, prix, stock, id_categorie, image_url) VALUES (:nom, :desc, :prix, :stock, :cat, :img)");
            $stmt->execute([
                'nom' => $nom, 'desc' => $desc, 'prix' => $prix, 'stock' => $stock, 'cat' => $id_cat, 'img' => $image
            ]);
        }
        header('Location: produits.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $id_produit > 0 ? 'Modifier' : 'Ajouter' ?> un Produit</title>
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
    <div class="auth-container" style="max-width: 600px;">
        <h2><?= $id_produit > 0 ? 'Editer le Produit #' . $id_produit : 'Nouveau Produit' ?></h2>

        <?php if ($error): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form action="produit_form.php<?= $id_produit > 0 ? '?id='.$id_produit : '' ?>" method="POST">
            <div class="form-group">
                <label for="nom_produit">Nom du produit *</label>
                <input type="text" id="nom_produit" name="nom_produit" value="<?= htmlspecialchars($produit['nom_produit']) ?>" required>
            </div>

            <div class="form-group">
                <label for="id_categorie">Catégorie *</label>
                <select name="id_categorie" id="id_categorie" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px;">
                    <option value="">-- Sélectionner une catégorie --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id_categorie'] ?>" <?= $cat['id_categorie'] == $produit['id_categorie'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom_categorie']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="display: flex; gap: 1rem;">
                <div style="flex: 1;">
                    <label for="prix">Prix (Ar) *</label>
                    <input type="number" step="0.01" min="0.01" id="prix" name="prix" value="<?= htmlspecialchars($produit['prix']) ?>" required>
                </div>
                <div style="flex: 1;">
                    <label for="stock">Quantité en Stock *</label>
                    <!-- AJOUT : min="1" empêche la saisie de nombres <= 0 directement dans le navigateur -->
                    <input type="number" min="1" id="stock" name="stock" value="<?= htmlspecialchars($produit['stock']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="image_url">Chemin / URL de l'image</label>
                <input type="text" id="image_url" name="image_url" placeholder="assets/images/ex.jpg" value="<?= htmlspecialchars($produit['image_url']) ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($produit['description']) ?></textarea>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; border: none; padding: 0.8rem; cursor: pointer;">
                Enregistrer le produit
            </button>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php';?>
