<!DOCTYPE html>

<html lang="fr">
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
    <link href="/css/hover.css" rel="stylesheet">

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
// Charger les données si on est connecté.
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $user = $_SESSION['username'];
    $type = $_SESSION['type'];
}
?>
<nav class="navbar navbar-index navbar-static-top nav-bar top-nav-collapse navbar-expand-lg">
    <?php
    $img = '../img/';
    include './navbar.php';
    ?>
</nav>

<section id="formations" class="page-wrapper">
    <div class="themeContainer">
    <?php
        $query = "";
        $queryType = "query";
        if( isset( $_GET['query'] )) {
            $query = htmlspecialchars($_GET['query']);
        } else {
            $query = htmlspecialchars($_GET['theme']);
            $queryType = "theme";
        }

        include 'data/formationLoader.php';

        if( sizeof($formations) == 0 ) {
            //Si jamais aucune formation n'a été trouvée avec la query
            echo "<div class=\"isa_error\"><i class=\"fa fa-times-circle\"></i>Aucune vidéo n'a été trouvée.</div>";
            echo " <button onclick=\"goBack()\" class='boxed-grey hvr-bounce-in hvr-overline-from-center' style='color: black; background-color: rgba(41,130,25,0.82); border-radius: 10% 10% 10% 10%;position: absolute;top: 20%;left: 47%;'>Retour</button>
                    <script>
                    function goBack() {
                      window.history.back();
                    }
                    </script> ";
        } else {
            //Si on a des vidéos.
            echo "<div class='isa_info'>Recherche ";
            if($queryType == "query")
                echo "par ".$query.".";
            else
                echo "dans le thème : [".$query."].";
            echo "</div>";
            $formationPerLine = 4;
            $lineCounter = 1;
            echo " <div class=\"row\">";
            foreach ($formations as $f) {
                $owner = $f->getOwner(); //Membre
                echo "<a href=\"../video.php?id=".$f->getID()."\" class=\"row formationBox\">";
                echo "<div class=\"topFormaBox\" style=\"background-image: url('".$f->getIconLink()."');\" >".$f->getVideoName()."</div>";
                echo "<div class=\"bottomFormaBox\">";
                if($owner->hasIcon())
                    echo "<img src=\"../data/accounts/".$owner->getID()."/icon.png\" class=\"leftCornerFormation\" />";
                else
                    echo "<img src=\"../img/logos/favicon.png\" class=\"leftCornerFormation\" />";
                echo "<div class=\"rightCornerFormation\">
                        ".$owner->getNom()."
                    </div>
                </div>
            </a>";

                $lineCounter ++;
                if($lineCounter == $formationPerLine+1) {
                    echo "</div>";
                    echo "<div class=\"row\" style=\"margin-top: 3vh;\">";
                    $lineCounter = 1;
                }
            }
            echo "</div>";
        }
    ?>
    </div>
</section>
<?php
include './footer.php';
?>

</body>
</html>
