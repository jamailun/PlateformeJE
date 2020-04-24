<?php

require 'classes/Formation.php';

$formations = array();

for ($i = 1; $i <= 8; $i++) {
    $formation = new Formation($i, "Formation ".$i);
    array_push($formations, $formation);
}
