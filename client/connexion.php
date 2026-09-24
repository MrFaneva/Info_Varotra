<?php
// Fichier : client/connexion.php

require_once '../config/database.php';
require_once '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (empty($email) || empty($mot_de_passe)) {
        $error = "Veuillez renseigner votre email et votre mot de passe.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM client WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $client = $stmt->fetch();

        if ($client && password_verify($mot_de_passe, $client['mot_de_passe'])) {
            // Regénération de l'ID de session pour prévenir la fixation de session
            session_regenerate_id(true);

            $_SESSION['client_id'] = $client['id_client'];
            $_SESSION['client_nom'] = $client['nom'] . ' ' . $client['prenom'];
            $_SESSION['client_email'] = $client['email'];

            header('Location: /Info_Varotra/index.php');
            exit();
        } else {
            $error = "Identifiants incorrects.";
        }
    }
}
?>

<main class="container">
    <div class="auth-container">
        <h2>Connexion Client</h2>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="connexion.php" method="POST">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; border: none; padding: 0.8rem; cursor: pointer; font-size: 1rem;">
                Se connecter
            </button>
        </form>

        <div class="auth-links">
            Pas encore de compte ? <a href="/Info_Varotra/client/inscription.php">Créer un compte</a>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
