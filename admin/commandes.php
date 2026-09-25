<?php
// Fichier : admin/commandes.php

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/login.php');
    exit();
}

// Mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_statut'])) {
    $id_cmd = (int)$_POST['id_commande'];
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("UPDATE commande SET statut = :statut WHERE id_commande = :id");
    $stmt->execute(['statut' => $statut, 'id' => $id_cmd]);
}

$commandes = $pdo->query("SELECT c.*, cl.nom, cl.prenom, cl.email 
                         FROM commande c 
                         JOIN client cl ON c.id_client = cl.id_client 
                         ORDER BY c.date_commande DESC")->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traitement des Commandes - Admin</title>
    <link rel="stylesheet" href="/Info_Varotra/assets/css/style.css">
</head>
<body>


<main class="container">
    <h2>Traitement des Commandes Clients</h2>

    <table style="width: 100%; border-collapse: collapse; background: white; margin-top: 1.5rem; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
        <thead>
            <tr style="background: var(--primary); color: white; text-align: left;">
                <th style="padding: 0.8rem;">Cmd #</th>
                <th style="padding: 0.8rem;">Client</th>
                <th style="padding: 0.8rem;">Date</th>
                <th style="padding: 0.8rem;">Montant Total</th>
                <th style="padding: 0.8rem;">Statut Actuel</th>
                <th style="padding: 0.8rem; text-align: center;">Changer le Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $cmd): ?>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 0.8rem; font-weight: bold;">#<?= $cmd['id_commande'] ?></td>
                    <td style="padding: 0.8rem;">
                        <?= htmlspecialchars($cmd['prenom'] . ' ' . $cmd['nom']) ?><br>
                        <small style="color: #64748b;"><?= htmlspecialchars($cmd['email']) ?></small>
                    </td>
                    <td style="padding: 0.8rem;"><?= date('d/m/Y H:i', strtotime($cmd['date_commande'])) ?></td>
                    <td style="padding: 0.8rem; color: var(--accent); font-weight: bold;"><?= number_format($cmd['montant_total'], 2, ',', ' ') ?> Ar</td>
                    <td style="padding: 0.8rem;"><span class="badge"><?= htmlspecialchars($cmd['statut']) ?></span></td>
                    <td style="padding: 0.8rem; text-align: center;">
                        <form action="commandes.php" method="POST" style="display: flex; gap: 0.5rem; justify-content: center;">
                            <input type="hidden" name="id_commande" value="<?= $cmd['id_commande'] ?>">
                            <select name="statut" style="padding: 0.3rem; border-radius: 4px; border: 1px solid var(--border-color);">
                                <option value="en_attente" <?= $cmd['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="validee" <?= $cmd['statut'] === 'validee' ? 'selected' : '' ?>>Validée</option>
                                <option value="expediee" <?= $cmd['statut'] === 'expediee' ? 'selected' : '' ?>>Expédiée</option>
                                <option value="livree" <?= $cmd['statut'] === 'livree' ? 'selected' : '' ?>>Livrée</option>
                                <option value="annulee" <?= $cmd['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                            </select>
                            <button type="submit" name="update_statut" class="btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/includes/footer.php';?>
