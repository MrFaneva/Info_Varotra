<?php
// Fichier : database/create_admin.php

// Inclure la configuration de la base de données
require_once '../config/database.php';

// Informations du super administrateur par défaut
$nom = 'Admin Principal';
$email = 'admin@infovarotra.local';
$mot_de_passe_clair = 'Admin123!'; // À changer en production !
$role = 'super_admin';

// Hachage du mot de passe
$mot_de_passe_hash = password_hash($mot_de_passe_clair, PASSWORD_BCRYPT);

try {
    // Vérifier si l'admin existe déjà pour éviter les doublons
    $stmt = $pdo->prepare("SELECT id_admin FROM administrateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->rowCount() > 0) {
        echo "L'administrateur avec l'email <strong>$email</strong> existe déjà dans la base de données.";
    } else {
        // Insertion de l'administrateur
        $insert = $pdo->prepare("INSERT INTO administrateur (nom, email, mot_de_passe, role) VALUES (:nom, :email, :mdp, :role)");
        $insert->execute([
            'nom' => $nom,
            'email' => $email,
            'mdp' => $mot_de_passe_hash,
            'role' => $role
        ]);
        echo "Le compte administrateur a été créé avec succès !<br>";
        echo "Email : " . $email . "<br>";
        echo "Mot de passe : " . $mot_de_passe_clair;
    }
} catch (PDOException $e) {
    echo "Erreur lors de la création de l'administrateur : " . $e->getMessage();
}
?>
