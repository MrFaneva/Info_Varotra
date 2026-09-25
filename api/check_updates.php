<?php
// Fichier : api/check_updates.php
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
require_once '../config/database.php';

try {
 
    $stmt_p = $pdo->query("SELECT id_produit, nom_produit, prix, stock FROM produit");
    $produits = $stmt_p->fetchAll(PDO::FETCH_ASSOC);
   
    $hash_prod = md5(json_encode($produits));

  
    $stmt_s = $pdo->query("SELECT id_service, nom_service, prix FROM service");
    $services = $stmt_s->fetchAll(PDO::FETCH_ASSOC);
    $hash_serv = md5(json_encode($services));

    echo json_encode([
        'success' => true,
        'hash_prod' => $hash_prod,
        'hash_serv' => $hash_serv
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
