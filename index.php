<?php
// Fichier : index.php

require_once 'config/database.php';
require_once 'includes/header.php';

// Récupération des services depuis la BDD
$stmt_services = $pdo->query("SELECT * FROM service ORDER BY id_service ASC");
$services = $stmt_services->fetchAll();

// Récupération des produits avec le nom de leur catégorie
$sql_produits = "SELECT p.*, c.nom_categorie 
                FROM produit p 
                JOIN categorie c ON p.id_categorie = c.id_categorie 
                ORDER BY p.id_produit DESC LIMIT 6";
$stmt_produits = $pdo->query($sql_produits);
$produits = $stmt_produits->fetchAll();
?>

<!-- Section Hero / Bannière d'accueil -->
<section class="hero">
    <h1>Solutions Informatiques & Matériels de Qualité</h1>
    <p>Achetez vos équipements informatiques et réservez nos services d'entretien ou de réparation en quelques clics.</p>
    <a href="#produits" class="btn-primary" style="padding: 0.8rem 1.5rem; font-size: 1rem;">Explorer le catalogue</a>
</section>

<main class="container">

    <!-- Section Nos Services -->
    <section id="services" style="margin-bottom: 4rem;">
        <h2 class="section-title">Nos Services Informatiques</h2>
        <div class="grid">
            <?php foreach ($services as $service): ?>
                <div class="card">
                    <div>
                        <h3><?= htmlspecialchars($service['nom_service']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                    <div>
                        <div class="badge">Durée : <?= htmlspecialchars($service['duree_estimee']) ?></div>
                        <div class="price"><?= number_format($service['prix'], 2, ',', ' ') ?> Ar</div>
                        <a href="/Info_Varotra/client/demande_service.php?id=<?= $service['id_service'] ?>" class="btn-primary" style="display: block; text-align: center; text-decoration: none;">Demander ce service</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section Nos Produits -->
    <section id="produits">
        <h2 class="section-title">Matériels en Vedette</h2>
        <div class="grid">
            <?php foreach ($produits as $produit): ?>
                <div class="card">
                    <div>
                        <span class="badge"><?= htmlspecialchars($produit['nom_categorie']) ?></span>
                        <h3><?= htmlspecialchars($produit['nom_produit']) ?></h3>
                        <p><?= htmlspecialchars($produit['description']) ?></p>
                    </div>
                    <div>
                        <div class="price"><?= number_format($produit['prix'], 2, ',', ' ') ?> Ar</div>
                        <a href="/Info_Varotra/client/panier.php?action=add&id=<?= $produit['id_produit'] ?>" class="btn-primary" style="display: block; text-align: center; text-decoration: none;">Ajouter au panier</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
