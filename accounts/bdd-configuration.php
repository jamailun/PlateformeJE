<?php

$server = "localhost";
$user = "website";
$password = "azertyUIOP//11";
$database = "bddForma";

/* error messages */
$messErr_connectionDatabaseFailed = "Error : connection failed. Please try later.";

echo "  A  ";

$link = new mysqli($server, $user, $password, $database);

echo " -> B";

if (!$link) {
    echo "IMPOSSIBLE DE SE CONNECTER A LA BDD.";
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
