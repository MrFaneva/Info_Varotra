<?php
// Fichier : admin/service_form.php

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

$id_service = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$service = [
    'nom_service' => '',
    'description' => '',
    'duree_estimee' => '',
    'prix' => ''
];
$error = '';

if ($id_service > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM service WHERE id_service = :id");
        $stmt->execute(['id' => $id_service]);
        $s = $stmt->fetch();
        if ($s) {
            $service = $s;
        }
    } catch (PDOException $e) {
        $error = "Erreur de lecture SQL : " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom_service'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $duree = trim($_POST['duree'] ?? '');
    $prix = (float)($_POST['prix'] ?? 0);

    if (empty($nom) || $prix <= 0) {
        $error = "Veuillez renseigner un nom de service et un prix valide.";
    } else {
        try {
            if ($id_service > 0) {
                $stmt = $pdo->prepare("UPDATE service SET nom_service = :nom, description = :desc, duree_estimee = :duree, prix = :prix WHERE id_service = :id");
                $stmt->execute([
                    'nom' => $nom,
                    'desc' => $desc,
                    'duree' => $duree,
                    'prix' => $prix,
                    'id' => $id_service
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO service (nom_service, description, duree_estimee, prix) VALUES (:nom, :desc, :duree, :prix)");
                $stmt->execute([
                    'nom' => $nom,
                    'desc' => $desc,
                    'duree' => $duree,
                    'prix' => $prix
                ]);
            }
            header('Location: services.php');
            exit();
        } catch (PDOException $e) {
            $error = "Erreur d'enregistrement SQL : " . $e->getMessage();
        }
    }
}

// 1. Titre dynamique passé au header de l'admin
$page_title = ($id_service > 0 ? 'Modifier' : 'Ajouter') . ' un Service - Admin';

// 2. Inclusion du Header ADMIN (admin/includes/header.php)
require_once __DIR__ . '/includes/header.php';
?>

<main class="container">
    <div class="auth-container" style="max-width: 600px; margin: 2rem auto;">
        <h2><?= $id_service > 0 ? 'Éditer le Service #' . $id_service : 'Nouveau Service' ?></h2>

        <?php if (!empty($error)): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; border: 1px solid #f87171;">
                <strong>Erreur :</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="service_form.php<?= $id_service > 0 ? '?id='.$id_service : '' ?>" method="POST">
            <div class="form-group">
                <label for="nom_service">Nom du service *</label>
                <input type="text" id="nom_service" name="nom_service" value="<?= htmlspecialchars($service['nom_service']) ?>" required>
            </div>

            <div class="form-group" style="display: flex; gap: 1rem;">
                <div style="flex: 1;">
                    <label for="duree">Durée estimée (ex: 2 heures, 24h à 48h)</label>
                    <input type="text" id="duree" name="duree" value="<?= htmlspecialchars($service['duree_estimee'] ?? '') ?>">
                </div>
                <div style="flex: 1;">
                    <label for="prix">Prix (Ar) *</label>
                    <input type="number" step="0.01" min="0.01" id="prix" name="prix" value="<?= htmlspecialchars($service['prix']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description du service</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($service['description'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; border: none; padding: 0.8rem; cursor: pointer; margin-top: 1rem;">
                Enregistrer le service
            </button>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php';?>
