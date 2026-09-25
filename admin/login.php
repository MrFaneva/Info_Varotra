<?php
// Fichier : admin/login.php

require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION['admin_id'])) {
    header('Location: /Info_Varotra/admin/index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (empty($email) || empty($mot_de_passe)) {
        $error = "Veuillez renseigner tous les champs.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM administrateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['admin_nom'] = $admin['nom'];
            $_SESSION['admin_role'] = $admin['role'];

            header('Location: /Info_Varotra/admin/index.php');
            exit();
        } else {
            $error = "Accès refusé : identifiants administrateur invalides.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Info_Varotra</title>
    <link rel="stylesheet" href="/Info_Varotra/assets/css/style.css">
</head>
<body style="background-color: #0f172a; display: flex; align-items: center; justify-content: center; min-height: 100vh;">

    <div class="auth-container" style="width: 100%; border: none;">
        <h2 style="color: #0f172a;">Espace Administration</h2>
        <p style="text-align: center; color: #64748b; margin-bottom: 1.5rem; font-size: 0.9rem;">Info_Varotra Back-Office</p>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email Admin</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; border: none; padding: 0.8rem; cursor: pointer; font-size: 1rem; background-color: #0f172a;">
                Connexion Back-Office
            </button>
        </form>

        <div class="auth-links">
            <a href="/Info_Varotra/index.php" style="color: #64748b;">&larr; Retour au site public</a>
        </div>
    </div>

</body>
</html>
