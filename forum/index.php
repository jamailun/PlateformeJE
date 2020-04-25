<!DOCTYPE HTML>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="#323044" name="theme-color">
    <meta content="Le forum" name="description">

    <title>Plateforme JE - Forum</title>
    <link href="/img/logos/cnje.png" rel="icon" type="image/png">

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="/css/bootstrap-slider.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/forum.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/hover.css" rel="stylesheet">

    <?php include '../GA.html'; ?>
</head>
<?php
// Charger les données si on est connecté.
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $user = $_SESSION['username'];
    $type = $_SESSION['type'];
    $connected = true;
}
?>
<body id="forum-page">
    <nav class="navbar navbar-index navbar-static-top nav-bar top-nav-collapse navbar-expand-lg">
        <?php
        $img = '../img/'; include '../navbar.php'; ?>
    </nav>
    <div class="left-block">
        <h3 class="left-block-title">Liste des topics</h3>
        <hr />
        <div><a href=""> Topic 1 </a></div>
        <hr />
        <div><a href=""> Topic 2 </a></div>
        <hr />
        <div><a href=""> Topic 3 </a></div>
        <hr />
    </div>
    <table class="right-block">
        <td class="right-block-column">
            Topics avec une activitée récente !
            <p>Découvre zles dernières trends' des jeunes de nos jours !</p>
        </td>
        <td class="right-block-column">
            <?php include '../configuration/forum-rules.html'; ?>
        </td>
    </table>


<?php include '../scripts.html'; ?>

</body>
</html>