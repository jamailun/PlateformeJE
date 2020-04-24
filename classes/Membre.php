<?php

class Membre implements Account {

    // string
    private $nom;

    // int
    private $id;

    public function __construct($id, $nom) {
        $this->id = $id;
        $this->nom = $nom;
        //Charger les données du dossier data/
    }

    public function getNom() {
        return $this->nom;
    }

    public function getID() {
        return $this->id;
    }

    public function getType() {
        return 1;
    }
}