<?php
// Fichier : client/commande.php

require_once '../config/database.php';
require_once '../includes/header.php';

// Redirection si le client n'est pas connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: /Info_Varotra/client/connexion.php');
    exit();
}

// Redirection si le panier est vide
if (empty($_SESSION['panier'])) {
    header('Location: /Info_Varotra/client/panier.php');
    exit();
}

$client_id = $_SESSION['client_id'];
$success = false;
$id_commande = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer_commande'])) {
    try {
        $pdo->beginTransaction();

        // 1. Calcul du montant total et vérification des prix
        $ids = array_keys($_SESSION['panier']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit IN ($placeholders)");
        $stmt->execute($ids);
        $produits_bdd = $stmt->fetchAll();

        $montant_total = 0;
        foreach ($produits_bdd as $prod) {
            $qty = $_SESSION['panier'][$prod['id_produit']];
            $montant_total += $prod['prix'] * $qty;
        }

        // 2. Création de la commande
        $stmt_cmd = $pdo->prepare("INSERT INTO commande (montant_total, id_client, statut) VALUES (:montant, :client, 'en_attente')");
        $stmt_cmd->execute([
            'montant' => $montant_total,
            'client' => $client_id
        ]);
        $id_commande = $pdo->lastInsertId();

        // 3. Insertion des lignes de commande
        $stmt_ligne = $pdo->prepare("INSERT INTO ligne_commande (id_commande, id_produit, quantite, prix_unitaire) VALUES (:cmd, :prod, :qty, :prix)");
        foreach ($produits_bdd as $prod) {
            $qty = $_SESSION['panier'][$prod['id_produit']];
            $stmt_ligne->execute([
                'cmd' => $id_commande,
                'prod' => $prod['id_produit'],
                'qty' => $qty,
                'prix' => $prod['prix']
            ]);
        }

        $pdo->commit();

        // Vider le panier après validation
        unset($_SESSION['panier']);
        $success = true;

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erreur lors de la validation de la commande : " . $e->getMessage();
    }
}
?>

<main class="container">
    <div class="auth-container" style="max-width: 600px;">
        <h2>Finalisation de la Commande</h2>

        <?php if ($success): ?>
            <div class="alert-success" style="text-align: center; padding: 2rem;">
                <h3 style="margin-bottom: 1rem;">Merci pour votre commande !</h3>
                <p>Votre commande <strong>#<?= $id_commande ?></strong> a été enregistrée avec succès.</p>
                <p style="margin-top: 1rem;"><a href="/Info_Varotra/client/espace.php" class="btn-primary" style="text-decoration: none;">Consulter mes commandes</a></p>
            </div>
        <?php else: ?>

            <?php if (isset($error)): ?>
                <div class="alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <p style="margin-bottom: 1.5rem;">Veuillez confirmer votre commande ci-dessous :</p>

            <div style="background: var(--bg-light); padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                <p><strong>Client :</strong> <?= htmlspecialchars($_SESSION['client_nom']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($_SESSION['client_email']) ?></p>
            </div>

            <form action="commande.php" method="POST">
                <button type="submit" name="confirmer_commande" class="btn-primary" style="width: 100%; border: none; padding: 0.9rem; cursor: pointer; font-size: 1.1rem;">
                    Confirmer et passer au paiement
                </button>
            </form>

        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
