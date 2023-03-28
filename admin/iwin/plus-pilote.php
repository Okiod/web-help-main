<?php 
    session_start();
    if(!isset($_SESSION['mark-page2'])){
        $_SESSION['mark-page2'] = 1;
        header("Location: //./admin/admin-pilote.php"); // redirige l'utilisateur
    }
    else{
        $_SESSION['mark-page2'] += 1;
        header("Location: //./admin/admin-pilote.php"); // redirige l'utilisateur
    }
?>