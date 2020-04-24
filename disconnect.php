<!DOCTYPE html>

<html lang="fr" xmlns="http://www.w3.org/1999/html">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="#323044" name="theme-color">
    <meta content="Plateforme de formation collaborative - par Junior-Entreprise." name="description">

    <title>JE - Plateforme collaborative</title>

    <link href="/img/logos/cnje.png" rel="icon" type="image/png">

    <!-- Open Graph Protocol -->

    <meta property='og:title' content="Junior-Entreprise : formations collaboratives">
    <meta property='og:description' content="Une plateforme pour les JE, afin d'aller plus loin plus vite">
    <meta property='og:type' content='website'>
    <meta property='og:locale' content='fr_FR'>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Owl Carousel -->

    <link href="/css/owl.carousel.css" rel="stylesheet" type="text/css">
    <link href="/css/owl.theme.default.css" rel="stylesheet" type="text/css">

    <!-- Fonts -->

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

    <!-- Theme CSS -->

    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">

    <?php
    include './GA.html';
    ?>
</head>

<body data-spy="scroll" data-target=".navbar" id="page-container">
<?php include './scripts.html'; ?>

<script src="/js/wow.min.js"></script>
<script src="/js/owl.carousel.js"></script>
<script src="/js/custom.js"></script>

<?php
include './accounts/deconnexion.php';
?>

<nav class="navbar navbar-index navbar-static-top nav-bar top-nav-collapse navbar-expand-lg">
    <?php
    include './navbar.php';
    ?>
</nav>

<section id="themes" class="page-wrapper">

    <!--
        Bon alors très franchement j'aurais aussi bien pu juste déconnecter sans passer par cette page, mais
        je trouve ça chouette de "marquer le coup" de la déconnection. à voir donc.
     -->

    <div class="block-center">

        Vous avez été déconnecté avec succès.

        <br />

        <button href="index.php" class="align-container">
            Revenir à la page principale.
        </button>

    </div>

</section>
<?php
$img = 'img/';
include './footer.php';
?>

</body>
</html>
