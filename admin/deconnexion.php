<?php
    session_start();
    $_SESSION = array();//Ecrase tableau de session 
    session_unset(); //Detruit toutes les variables de la session en cours
    session_destroy();//Destruit la session en cours
    header("Location: ./connexion.php"); // redirige l'utilisateur
?>