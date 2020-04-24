<?php

    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("Location: ../index.php");
        return;
    }

    if( ! isset($_POST['username']) || ! isset($_POST['password'])) {
        header('Location: index.php'); // on est pas censé être là
        return;
    }
    $db_username = 'root';

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    if($username == "" || $password === "") {
        header('Location: index.php?erreur=2'); // utilisateur ou mot de passe vide
        return;
    }

    if( $username == "je" && $password == "toto" ) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['type'] = 0;
        header('Location: ../index.php');
        return;
    }

    if( $username == "louis" && $password == "toto" ) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['type'] = 1;
        header('Location: ../index.php');
        return;
    }

    header('Location: index.php?erreur=1'); // utilisateur ou mot de passe incorrect
//mysqli_close($db); // fermer la connexion
