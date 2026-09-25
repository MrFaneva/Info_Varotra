<?php
// Fichier : admin/demandes.php

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/login.php');
    exit();
}

// Mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_statut_service'])) {
    $id_demande = (int)$_POST['id_demande'];
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("UPDATE demande_service SET statut = :statut WHERE id_demande = :id");
    $stmt->execute(['statut' => $statut, 'id' => $id_demande]);
}

$demandes = $pdo->query("SELECT ds.*, s.nom_service, cl.nom, cl.prenom, cl.email 
                        FROM demande_service ds 
                        JOIN service s ON ds.id_service = s.id_service 
                        JOIN client cl ON ds.id_client = cl.id_client 
                        ORDER BY ds.date_rdv ASC")->fetchAll();
require_once __DIR__ . '/includes/header.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traitement des Rendez-vous - Admin</title>
    <link rel="stylesheet" href="/Info_Varotra/assets/css/style.css">
</head>
<body>


<main class="container">
    <h2>Demandes de Services & Rendez-vous</h2>

    <table style="width: 100%; border-collapse: collapse; background: white; margin-top: 1.5rem; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
        <thead>
            <tr style="background: var(--primary); color: white; text-align: left;">
                <th style="padding: 0.8rem;">Client</th>
                <th style="padding: 0.8rem;">Service</th>
                <th style="padding: 0.8rem;">Date RDV</th>
                <th style="padding: 0.8rem;">Problème décrit</th>
                <th style="padding: 0.8rem;">Statut</th>
                <th style="padding: 0.8rem; text-align: center;">Changer le Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $dm): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 0.8rem;">
                        <?= htmlspecialchars($dm['prenom'] . ' ' . $dm['nom']) ?><br>
                        <small style="color: #64748b;"><?= htmlspecialchars($dm['email']) ?></small>
                    </td>
                    <td style="padding: 0.8rem; font-weight: bold;"><?= htmlspecialchars($dm['nom_service']) ?></td>
                    <td style="padding: 0.8rem;"><?= date('d/m/Y H:i', strtotime($dm['date_rdv'])) ?></td>
                    <td style="padding: 0.8rem; max-width: 250px; font-size: 0.9rem; color: #475569;"><?= htmlspecialchars($dm['description_probleme']) ?></td>
                    <td style="padding: 0.8rem;"><span class="badge"><?= htmlspecialchars($dm['statut']) ?></span></td>
                    <td style="padding: 0.8rem; text-align: center;">
                        <form action="demandes.php" method="POST" style="display: flex; gap: 0.5rem; justify-content: center;">
                            <input type="hidden" name="id_demande" value="<?= $dm['id_demande'] ?>">
                            <select name="statut" style="padding: 0.3rem; border-radius: 4px; border: 1px solid var(--border-color);">
                                <option value="en_attente" <?= $dm['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="confirmee" <?= $dm['statut'] === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                                <option value="terminee" <?= $dm['statut'] === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                                <option value="annulee" <?= $dm['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                            </select>
                            <button type="submit" name="update_statut_service" class="btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/includes/footer.php';?>
