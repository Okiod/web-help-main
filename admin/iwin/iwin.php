<?php
    session_start();
    if(!isset($_SESSION['mark-page'])){
        $_SESSION['mark-page'] = 1;
        header("Location: //./admin/admin-student2.php"); // redirige l'utilisateur
    }
    else{
        $_SESSION['mark-page'] += 1;
        header("Location: //./admin/admin-student2.php"); // redirige l'utilisateur
    }
?>