<?php
// Fichier : api/get_catalog.php
require_once '../config/database.php';

$type = $_GET['type'] ?? '';

if ($type === 'produits') {
    $sql = "SELECT p.*, c.nom_categorie 
            FROM produit p 
            JOIN categorie c ON p.id_categorie = c.id_categorie 
            ORDER BY p.id_produit DESC";
    $produits = $pdo->query($sql)->fetchAll();

    foreach ($produits as $produit): ?>
        <div class="card carousel-item">
            <div>
                <?php if (!empty($produit['image_url'])): ?>
                    <img src="<?= htmlspecialchars($produit['image_url']) ?>" 
                         alt="<?= htmlspecialchars($produit['nom_produit']) ?>" 
                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <?php else: ?>
                    <div style="width: 100%; height: 200px; background-color: #f1f5f9; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                        <span>Aucune image</span>
                    </div>
                <?php endif; ?>

                <span class="badge"><?= htmlspecialchars($produit['nom_categorie']) ?></span>
                <h3 style="margin-top: 0.5rem;"><?= htmlspecialchars($produit['nom_produit']) ?></h3>
                <p><?= htmlspecialchars($produit['description']) ?></p>
            </div>
            <div style="margin-top: 1rem;">
                <div class="price"><?= number_format($produit['prix'], 2, ',', ' ') ?> Ar</div>
                <a href="/Info_Varotra/client/panier.php?action=add&id=<?= $produit['id_produit'] ?>" class="btn-primary" style="display: block; text-align: center; text-decoration: none;">Ajouter au panier</a>
            </div>
        </div>
    <?php endforeach;

} elseif ($type === 'services') {
    $services = $pdo->query("SELECT * FROM service ORDER BY id_service ASC")->fetchAll();

    foreach ($services as $service): ?>
        <div class="card carousel-item">
            <div>
                <h3><?= htmlspecialchars($service['nom_service']) ?></h3>
                <p><?= htmlspecialchars($service['description']) ?></p>
            </div>
            <div style="margin-top: 1rem;">
                <div class="badge">Durée : <?= htmlspecialchars($service['duree_estimee']) ?></div>
                <div class="price"><?= number_format($service['prix'], 2, ',', ' ') ?> Ar</div>
                <a href="/Info_Varotra/client/demande_service.php?id=<?= $service['id_service'] ?>" class="btn-primary" style="display: block; text-align: center; text-decoration: none;">Demander ce service</a>
            </div>
        </div>
    <?php endforeach;
}
