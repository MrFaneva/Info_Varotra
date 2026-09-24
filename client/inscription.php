<?php
// Fichier : client/inscription.php

require_once '../config/database.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');

    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        $error = "Veuillez remplir tous les champs obligatoires (*).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format d'adresse email invalide.";
    } else {
        // Vérification de l'unicité de l'email
        $stmt = $pdo->prepare("SELECT id_client FROM client WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $error = "Cette adresse email est déjà associée à un compte.";
        } else {
            // Hachage du mot de passe
            $mdp_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

            // Insertion en BDD
            $sql = "INSERT INTO client (nom, prenom, email, mot_de_passe, telephone, adresse) 
                    VALUES (:nom, :prenom, :email, :mdp, :tel, :adresse)";
            $insert = $pdo->prepare($sql);
            $result = $insert->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mdp' => $mdp_hash,
                'tel' => $telephone,
                'adresse' => $adresse
            ]);

            if ($result) {
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<main class="container">
    <div class="auth-container">
        <h2>Créer un compte</h2>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert-success">
                <?= htmlspecialchars($success) ?><br><br>
                <a href="/Info_Varotra/client/connexion.php" class="btn-primary" style="display: inline-block; text-align: center; text-decoration: none;">Se connecter</a>
            </div>
        <?php else: ?>

            <form action="inscription.php" method="POST">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">Adresse Email *</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe *</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse de livraison</label>
                    <textarea id="adresse" name="adresse" rows="3"><?= htmlspecialchars($_POST['adresse'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; border: none; padding: 0.8rem; cursor: pointer; font-size: 1rem;">
                    S'inscrire
                </button>
            </form>

            <div class="auth-links">
                Vous avez déjà un compte ? <a href="/Info_Varotra/client/connexion.php">Se connecter</a>
            </div>

        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
