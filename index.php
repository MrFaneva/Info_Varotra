<?php
// Fichier : index.php

require_once 'config/database.php';
require_once 'includes/header.php';

// Récupération de TOUS les services depuis la BDD
$stmt_services = $pdo->query("SELECT * FROM service ORDER BY id_service ASC");
$services = $stmt_services->fetchAll();

// Récupération de TOUS les produits avec leur catégorie
$sql_produits = "SELECT p.*, c.nom_categorie 
                FROM produit p 
                JOIN categorie c ON p.id_categorie = c.id_categorie 
                ORDER BY p.id_produit DESC";
$stmt_produits = $pdo->query($sql_produits);
$produits = $stmt_produits->fetchAll();
?>

<!-- Style spécifique pour le défilement horizontal (Carrousel) -->
<style>
/* Conteneur du défilement horizontal */
.carousel-track {
    display: flex;
    gap: 1.5rem;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 0.5rem 0.2rem 1.5rem 0.2rem;
    /* Masquer la barre de défilement par défaut */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE / Edge */
}

.carousel-track::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

/* Fixer la largeur de chaque carte pour qu'elles restent alignées horizontalement */
.carousel-item {
    flex: 0 0 300px;
    max-width: 300px;
}

/* Style des boutons de navigation (Flèches Précédent / Suivant) */
.btn-carousel {
    padding: 0.5rem 1.2rem;
    border: 2px solid #2563eb;
    background: #ffffff;
    color: #2563eb;
    border-radius: 30px;
    cursor: pointer;
    font-weight: bold;
    font-size: 0.9rem;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.btn-carousel:hover {
    background: #2563eb;
    color: #ffffff;
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.25);
}
</style>

<!-- Section Hero / Bannière d'accueil -->
<section class="hero">
    <h1>Solutions Informatiques & Matériels de Qualité</h1>
    <p>Achetez vos équipements informatiques et réservez nos services d'entretien ou de réparation en quelques clics.</p>
    <a href="#produits" class="btn-primary" style="padding: 0.8rem 1.5rem; font-size: 1rem;">Explorer le catalogue</a>
</section>

<main class="container">

    <!-- Section Nos Services (Carrousel) -->
    <section id="services" style="margin-bottom: 4rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 class="section-title" style="margin-bottom: 0;">Nos Services Informatiques</h2>
            
            <!-- Boutons de navigation Flèches -->
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="scrollCarousel('services-track', -320)" class="btn-carousel" title="Précédent">❮ Précédent</button>
                <button onclick="scrollCarousel('services-track', 320)" class="btn-carousel" title="Suivant">Suivant ❯</button>
            </div>
        </div>

        <div id="services-track" class="carousel-track">
            <?php foreach ($services as $service): ?>
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
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Section Nos Produits (Carrousel) -->
    <section id="produits" style="margin-bottom: 4rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 class="section-title" style="margin-bottom: 0;">Matériels en Vedette</h2>
            
            <!-- Boutons de navigation Flèches -->
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="scrollCarousel('produits-track', -320)" class="btn-carousel" title="Précédent">❮ Précédent</button>
                <button onclick="scrollCarousel('produits-track', 320)" class="btn-carousel" title="Suivant">Suivant ❯</button>
            </div>
        </div>

        <div id="produits-track" class="carousel-track">
            <?php foreach ($produits as $produit): ?>
                <div class="card carousel-item">
                    <div>
                        <!-- Image du produit -->
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
            <?php endforeach; ?>
        </div>
    </section>

</main>

<!-- Script JavaScript pour le défilement horizontal fluide -->
<script>
function scrollCarousel(containerId, distance) {
    const container = document.getElementById(containerId);
    if (container) {
        container.scrollBy({
            left: distance,
            behavior: 'smooth'
        });
    }
}
</script>
<script>
// Stockage des dernières empreintes connues
let knownProdHash = null;
let knownServHash = null;

function checkUpdates() {
    // Utilisation d'un chemin relatif et d'un paramètre anti-cache (Date.now())
    fetch('api/check_updates.php?t=' + Date.now())
        .then(response => response.json())
        .then(data => {
            if (!data.success) return;

            // Premier chargement : on initialise
            if (knownProdHash === null && knownServHash === null) {
                knownProdHash = data.hash_prod;
                knownServHash = data.hash_serv;
                return;
            }

            // Détection d'un changement dans les produits (ajout ou modification)
            if (data.hash_prod !== knownProdHash) {
                knownProdHash = data.hash_prod;
                refreshContainer('produits-track', 'produits');
            }

            // Détection d'un changement dans les services
            if (data.hash_serv !== knownServHash) {
                knownServHash = data.hash_serv;
                refreshContainer('services-track', 'services');
            }
        })
        .catch(err => console.error('Erreur vérification mises à jour:', err));
}

function refreshContainer(containerId, type) {
    // On ajoute également l'anti-cache ici pour forcer le rechargement du HTML
    fetch('api/get_catalog.php?type=' + type + '&t=' + Date.now())
        .then(response => response.text())
        .then(html => {
            const container = document.getElementById(containerId);
            if (container) {
                container.innerHTML = html;
            }
        })
        .catch(err => console.error('Erreur chargement catalogue:', err));
}

// Vérification en arrière-plan toutes les 4 secondes
setInterval(checkUpdates, 4000);
</script>

<?php require_once 'includes/footer.php'; ?>
