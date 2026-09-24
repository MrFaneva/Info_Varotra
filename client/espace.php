<?php
// Fichier : client/espace.php

require_once '../config/database.php';
require_once '../includes/header.php';

// Redirection si le client n'est pas connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: /Info_Varotra/client/connexion.php');
    exit();
}

$client_id = $_SESSION['client_id'];
$message_success = '';
$message_error = '';

$action = $_GET['action'] ?? '';
$id_target = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// --- 1. SUPPRESSION D'UNE COMMANDE DE MATÉRIEL ---
if ($action === 'supprimer_commande' && $id_target > 0) {
    try {
        $pdo->beginTransaction();

        $stmtCheck = $pdo->prepare("SELECT id_commande FROM commande WHERE id_commande = :id AND id_client = :client AND statut = 'en_attente'");
        $stmtCheck->execute(['id' => $id_target, 'client' => $client_id]);

        if ($stmtCheck->fetch()) {
            // Suppression des lignes puis de la commande
            $pdo->prepare("DELETE FROM ligne_commande WHERE id_commande = :id")->execute(['id' => $id_target]);
            $pdo->prepare("DELETE FROM commande WHERE id_commande = :id")->execute(['id' => $id_target]);
            $pdo->commit();
            $message_success = "La commande #{$id_target} a été annulée et supprimée avec succès.";
        } else {
            $pdo->rollBack();
            $message_error = "Impossible de supprimer cette commande (elle a déjà été traitée ou n'existe pas).";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $message_error = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

// --- 2. SUPPRESSION D'UNE DEMANDE DE SERVICE ---
if ($action === 'supprimer_service' && $id_target > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM demande_service WHERE id_demande = :id AND id_client = :client AND statut = 'en_attente'");
        $stmt->execute(['id' => $id_target, 'client' => $client_id]);

        if ($stmt->rowCount() > 0) {
            $message_success = "La demande de rendez-vous pour le service a été annulée avec succès.";
        } else {
            $message_error = "Impossible d'annuler ce rendez-vous (il a déjà été confirmé ou n'existe pas).";
        }
    } catch (Exception $e) {
        $message_error = "Erreur lors de l'annulation du service : " . $e->getMessage();
    }
}

// --- RÉCUPÉRATION DES DONNÉES ---
$stmt_client = $pdo->prepare("SELECT * FROM client WHERE id_client = :id");
$stmt_client->execute(['id' => $client_id]);
$client_info = $stmt_client->fetch();

// Commandes de matériels
$stmt_commandes = $pdo->prepare("SELECT * FROM commande WHERE id_client = :id ORDER BY date_commande DESC");
$stmt_commandes->execute(['id' => $client_id]);
$commandes = $stmt_commandes->fetchAll();

// Demandes de services
$stmt_services = $pdo->prepare("SELECT ds.*, s.nom_service 
                                FROM demande_service ds 
                                JOIN service s ON ds.id_service = s.id_service 
                                WHERE ds.id_client = :id 
                                ORDER BY ds.date_demande DESC");
$stmt_services->execute(['id' => $client_id]);
$demandes = $stmt_services->fetchAll();
?>

<main class="container">
    <div style="margin-bottom: 2rem;">
        <h2>Espace Personnel</h2>
        <p style="color: #64748b;">Bienvenue, <strong><?= htmlspecialchars($client_info['prenom'] . ' ' . $client_info['nom']) ?></strong> (<?= htmlspecialchars($client_info['email']) ?>)</p>
    </div>

    <!-- Messages de confirmation ou d'erreur -->
    <?php if ($message_success): ?>
        <div style="margin-bottom: 1.5rem; padding: 1rem; background: #dcfce7; color: #15803d; border-radius: 6px; border: 1px solid #bbf7d0;">
            <?= htmlspecialchars($message_success) ?>
        </div>
    <?php endif; ?>

    <?php if ($message_error): ?>
        <div style="margin-bottom: 1.5rem; padding: 1rem; background: #fee2e2; color: #b91c1c; border-radius: 6px; border: 1px solid #fca5a5;">
            <?= htmlspecialchars($message_error) ?>
        </div>
    <?php endif; ?>

    <!-- Section Commandes de Matériels -->
    <section style="margin-bottom: 3rem;">
        <h3>Mes Commandes de Matériels</h3>
        <?php if (empty($commandes)): ?>
            <p style="color: #64748b; margin-top: 1rem;">Vous n'avez aucune commande enregistrée.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; background: white; margin-top: 1rem; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
                <thead>
                    <tr style="background-color: var(--primary); color: white; text-align: left;">
                        <th style="padding: 0.8rem;">N° Cmd</th>
                        <th style="padding: 0.8rem;">Date</th>
                        <th style="padding: 0.8rem;">Montant Total</th>
                        <th style="padding: 0.8rem;">Statut</th>
                        <th style="padding: 0.8rem; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $cmd): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.8rem; font-weight: bold;">#<?= $cmd['id_commande'] ?></td>
                            <td style="padding: 0.8rem;"><?= date('d/m/Y H:i', strtotime($cmd['date_commande'])) ?></td>
                            <td style="padding: 0.8rem; color: var(--accent); font-weight: bold;"><?= number_format($cmd['montant_total'], 2, ',', ' ') ?> Ar</td>
                            <td style="padding: 0.8rem;">
                                <span class="badge"><?= htmlspecialchars($cmd['statut']) ?></span>
                            </td>
                            <td style="padding: 0.8rem; text-align: center;">
                                <?php if ($cmd['statut'] === 'en_attente'): ?>
                                    <a href="espace.php?action=supprimer_commande&id=<?= $cmd['id_commande'] ?>" 
                                       onclick="return confirm('Voulez-vous vraiment annuler et supprimer la commande #<?= $cmd['id_commande'] ?> ?');" 
                                       style="color: #dc2626; text-decoration: none; font-weight: bold; border: 1px solid #dc2626; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.85rem;">
                                        Supprimer
                                    </a>
                                <?php else: ?>
                                    <span style="color: #94a3b8; font-size: 0.85rem;">Non modifiable</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <!-- Section Demandes de Services -->
    <section>
        <h3>Mes Demandes de Services Informatiques</h3>
        <?php if (empty($demandes)): ?>
            <p style="color: #64748b; margin-top: 1rem;">Vous n'avez fait aucune demande de service pour le moment.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; background: white; margin-top: 1rem; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
                <thead>
                    <tr style="background-color: var(--primary); color: white; text-align: left;">
                        <th style="padding: 0.8rem;">Service</th>
                        <th style="padding: 0.8rem;">Date RDV</th>
                        <th style="padding: 0.8rem;">Problème décrit</th>
                        <th style="padding: 0.8rem;">Statut</th>
                        <th style="padding: 0.8rem; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($demandes as $dm): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.8rem; font-weight: bold;"><?= htmlspecialchars($dm['nom_service']) ?></td>
                            <td style="padding: 0.8rem;"><?= date('d/m/Y H:i', strtotime($dm['date_rdv'])) ?></td>
                            <td style="padding: 0.8rem; max-width: 250px;"><?= htmlspecialchars($dm['description_probleme']) ?></td>
                            <td style="padding: 0.8rem;">
                                <span class="badge"><?= htmlspecialchars($dm['statut']) ?></span>
                            </td>
                            <td style="padding: 0.8rem; text-align: center;">
                                <?php if ($dm['statut'] === 'en_attente'): ?>
                                    <a href="espace.php?action=supprimer_service&id=<?= $dm['id_demande'] ?>" 
                                       onclick="return confirm('Voulez-vous vraiment annuler votre rendez-vous pour <?= htmlspecialchars($dm['nom_service']) ?> ?');" 
                                       style="color: #dc2626; text-decoration: none; font-weight: bold; border: 1px solid #dc2626; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.85rem;">
                                        Annuler
                                    </a>
                                <?php else: ?>
                                    <span style="color: #94a3b8; font-size: 0.85rem;">Non modifiable</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
