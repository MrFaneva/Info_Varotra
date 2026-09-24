<?php
// Fichier : config/database.php

$host = '127.0.0.1'; // ou 'localhost'
$dbname = 'Info_Varotra';
$username = 'root'; // Par défaut sur XAMPP/WAMP
$password = 'Henintsoa0501!'; // Vide par défaut sur XAMPP/WAMP (à modifier selon ta config locale)

try {
    // Création de l'instance PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Afficher les erreurs SQL
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Récupérer les données sous forme de tableau associatif
    
} catch (PDOException $e) {
    // En cas d'erreur de connexion, on arrête l'exécution et on affiche un message
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
