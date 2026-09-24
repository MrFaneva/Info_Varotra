<?php
// Fichier : admin/deconnexion.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['admin_id']);
unset($_SESSION['admin_nom']);
unset($_SESSION['admin_role']);

header('Location: /Info_Varotra/admin/login.php');
exit();
?>
