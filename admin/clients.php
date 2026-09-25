<?php
// Fichier : admin/clients.php

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification des droits d'accès admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/login.php');
    exit();
}

// Récupération de la liste des clients (du plus récent au plus ancien)

$stmt = $pdo->query("SELECT * FROM client ORDER BY id_client DESC");
$clients = $stmt->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients - Administration Info_Varotra</title>
    <link rel="stylesheet" href="/Info_Varotra/assets/css/style.css">
</head>
<body>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Liste des Clients Inscrits</h2>
        <span style="background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold;">
            Total : <?= count($clients) ?> client(s)
        </span>
    </div>

    <?php if (empty($clients)): ?>
        <p style="color: #64748b; background: white; padding: 2rem; text-align: center; border-radius: 8px;">Aucun client n'est encore inscrit sur le site.</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
                <thead>
                    <tr style="background-color: var(--primary); color: white; text-align: left;">
                        <th style="padding: 1rem;">N°</th>
                        <th style="padding: 1rem;">Nom & Prénom</th>
                        <th style="padding: 1rem;">Email</th>
                        <th style="padding: 1rem;">Téléphone</th>
                        <th style="padding: 1rem;">Adresse</th>
                        <th style="padding: 1rem; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $num = 1;
                    foreach ($clients as $client): 
                    ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem; font-weight: bold; color: #64748b;"><?= $num++ ?></td>
                            <td style="padding: 1rem; font-weight: bold;">
                                <?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom']) ?>
                            </td>
                            <td style="padding: 1rem;">
                                <a href="mailto:<?= htmlspecialchars($client['email']) ?>" style="color: #2563eb; text-decoration: none;">
                                    <?= htmlspecialchars($client['email']) ?>
                                </a>
                            </td>
                            <td style="padding: 1rem;"><?= htmlspecialchars($client['telephone']) ?: '<span style="color:#cbd5e1;">Non renseigné</span>' ?></td>
                            <td style="padding: 1rem; max-width: 250px;"><?= htmlspecialchars($client['adresse']) ?: '<span style="color:#cbd5e1;">Non renseignée</span>' ?></td>
                            <td style="padding: 1rem; text-align: center;">
                                <!-- Lien pour envoyer un mail directement via le client de messagerie -->
                                <a href="mailto:<?= htmlspecialchars($client['email']) ?>" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-radius: 4px; text-decoration: none;">
                                    Contacter
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php';?>
