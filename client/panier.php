<?php
// Fichier : client/panier.php

require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$action = $_GET['action'] ?? '';
$id_produit = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 1. Ajouter un produit au panier
if ($action === 'add' && $id_produit > 0) {
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit]++;
    } else {
        $_SESSION['panier'][$id_produit] = 1;
    }
    header('Location: panier.php');
    exit();
}

// 2. Supprimer un produit du panier
if ($action === 'remove' && $id_produit > 0) {
    unset($_SESSION['panier'][$id_produit]);
    header('Location: panier.php');
    exit();
}

// 3. Mettre à jour les quantités
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quantite']) && is_array($_POST['quantite'])) {
        foreach ($_POST['quantite'] as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;
            if ($qty > 0) {
                $_SESSION['panier'][$id] = $qty;
            } else {
                unset($_SESSION['panier'][$id]);
            }
        }
    }
    header('Location: panier.php');
    exit();
}

// Récupération des données produits en BDD
$produits_panier = [];
$total_panier = 0;

if (!empty($_SESSION['panier'])) {
    $ids = array_keys($_SESSION['panier']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    
    $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit IN ($placeholders)");
    $stmt->execute($ids);
    $produits_bdd = $stmt->fetchAll();

    foreach ($produits_bdd as $prod) {
        $qty = $_SESSION['panier'][$prod['id_produit']];
        $sous_total = $prod['prix'] * $qty;
        $total_panier += $sous_total;

        $produits_panier[] = [
            'id_produit' => $prod['id_produit'],
            'nom_produit' => $prod['nom_produit'],
            'prix' => $prod['prix'],
            'quantite' => $qty,
            'sous_total' => $sous_total
        ];
    }
}
?>

<main class="container">
    <h2 class="section-title">Votre Panier</h2>

    <?php if (empty($produits_panier)): ?>
        <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px; border: 1px solid var(--border-color);">
            <p style="font-size: 1.2rem; color: #64748b; margin-bottom: 1.5rem;">Votre panier est actuellement vide.</p>
            <a href="/Info_Varotra/index.php#produits" class="btn-primary" style="text-decoration: none;">Découvrir nos produits</a>
        </div>
    <?php else: ?>

        <form action="panier.php?action=update" method="POST">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 2rem;">
                <thead>
                    <tr style="background-color: var(--primary); color: white; text-align: left;">
                        <th style="padding: 1rem;">Produit</th>
                        <th style="padding: 1rem;">Prix Unitaire</th>
                        <th style="padding: 1rem;">Quantité</th>
                        <th style="padding: 1rem;">Sous-total</th>
                        <th style="padding: 1rem; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits_panier as $item): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem; font-weight: 600;"><?= htmlspecialchars($item['nom_produit']) ?></td>
                            <td style="padding: 1rem;"><?= number_format($item['prix'], 2, ',', ' ') ?> Ar</td>
                            <td style="padding: 1rem;">
                                <input type="number" name="quantite[<?= $item['id_produit'] ?>]" value="<?= $item['quantite'] ?>" min="1" style="width: 70px; padding: 0.4rem; border: 1px solid var(--border-color); border-radius: 4px;">
                            </td>
                            <td style="padding: 1rem; font-weight: bold; color: var(--accent);"><?= number_format($item['sous_total'], 2, ',', ' ') ?> Ar</td>
                            <td style="padding: 1rem; text-align: center;">
                                <a href="panier.php?action=remove&id=<?= $item['id_produit'] ?>" style="color: #dc2626; text-decoration: none; font-weight: bold;">✕ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-color);">
                <button type="submit" class="btn-primary" style="background-color: #64748b; border: none; padding: 0.7rem 1.2rem; cursor: pointer;">Mettre à jour le panier</button>
                
                <div style="text-align: right;">
                    <p style="font-size: 1.3rem; font-weight: bold; margin-bottom: 0.5rem;">
                        Total : <span style="color: var(--accent);"><?= number_format($total_panier, 2, ',', ' ') ?> Ar</span>
                    </p>
                    <a href="commande.php" class="btn-primary" style="display: inline-block; padding: 0.8rem 1.5rem; text-decoration: none; font-size: 1.05rem;">Valider la commande &rarr;</a>
                </div>
            </div>
        </form>

    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>
