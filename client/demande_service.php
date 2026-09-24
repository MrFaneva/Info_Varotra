<?php
// Fichier : client/demande_service.php

require_once '../config/database.php';
require_once '../includes/header.php';

// Connexion obligatoire pour demander un service
if (!isset($_SESSION['client_id'])) {
    header('Location: /Info_Varotra/client/connexion.php');
    exit();
}

$id_service_select = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = '';

// Récupération de tous les services disponibles pour la liste déroulante
$services = $pdo->query("SELECT * FROM service ORDER BY nom_service ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_service = (int)($_POST['id_service'] ?? 0);
    $date_rdv = $_POST['date_rdv'] ?? '';
    $description = trim($_POST['description_probleme'] ?? '');

    // Vérification de la date et de l'heure
    $timestamp_rdv = strtotime($date_rdv);
    $timestamp_min = time() + 3600; // Heure actuelle + 3600 secondes (1 heure)

    if ($id_service <= 0 || empty($date_rdv) || empty($description)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif ($timestamp_rdv < $timestamp_min) {
        $error = "Le rendez-vous doit être fixé à au moins 1 heure à partir de maintenant.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO demande_service (date_rdv, description_probleme, id_client, id_service, statut) VALUES (:rdv, :desc, :client, :service, 'en_attente')");
        $result = $stmt->execute([
            'rdv' => $date_rdv,
            'desc' => $description,
            'client' => $_SESSION['client_id'],
            'service' => $id_service
        ]);

        if ($result) {
            $success = "Votre demande de service a bien été envoyée ! Un technicien la traitera dans les plus brefs délais.";
        } else {
            $error = "Une erreur s'est produite lors de la soumission de la demande.";
        }
    }
}

// Calcul de la date minimum pour l'attribut HTML "min"
$min_datetime = date('Y-m-d\TH:i', time() + 3600);
?>

<main class="container">
    <div class="auth-container" style="max-width: 550px;">
        <h2>Demande de Service Informatique</h2>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert-success">
                <?= htmlspecialchars($success) ?><br><br>
                <a href="/Info_Varotra/client/espace.php" class="btn-primary" style="display: inline-block; text-decoration: none;">Voir mes rendez-vous</a>
            </div>
        <?php else: ?>

            <form action="demande_service.php" method="POST">
                <div class="form-group">
                    <label for="id_service">Service souhaité *</label>
                    <select name="id_service" id="id_service" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px;">
                        <option value="">-- Sélectionnez un service --</option>
                        <?php foreach ($services as $srv): ?>
                            <option value="<?= $srv['id_service'] ?>" <?= ($srv['id_service'] == $id_service_select) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($srv['nom_service']) ?> (<?= number_format($srv['prix'], 2, ',', ' ') ?> Ar)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date_rdv">Date & Heure du Rendez-vous souhaité *</label>
                    <input type="datetime-local" id="date_rdv" name="date_rdv" required min="<?= $min_datetime ?>" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px;">
                    <small style="color: #64748b; font-size: 0.85rem; display: block; margin-top: 0.3rem;">
                        Veuillez prévoir un délai minimum de 1 heure.
                    </small>
                </div>

                <div class="form-group">
                    <label for="description_probleme">Description du problème ou de votre besoin *</label>
                    <textarea id="description_probleme" name="description_probleme" rows="4" placeholder="Ex: Mon PC portable ne s'allume plus après une mise à jour..." required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 6px;"></textarea>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; border: none; padding: 0.8rem; cursor: pointer; font-size: 1rem;">
                    Envoyer la demande
                </button>
            </form>

        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
