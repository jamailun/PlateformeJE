<?php

require 'Account.php';

class Membre implements Account {

    // string
    private $nom;

    // string
    private $id;

    public function __construct($id) {
        $this->id = $id;
        $this->loadData();
    }

    private function loadData() {
        $fileDataM = getcwd().'/data/accounts/'.$this->id.'/data.xml';
        if ( file_exists($fileDataM) ) {
            $xml = new SimpleXMLElement($fileDataM, NULL, TRUE);
            $this->nom = $xml->nom;
        } else {
            syslog(LOG_WARNING, 'Echec lors de l\'ouverture du fichier ('.$fileDataM.').');
        }
    }

    public function hasIcon() {
        if(file_exists('../data/accounts/'.$this->id.'/icon.png'))
            return true;
        return false;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getID() {
        return $this->id;
    }

    /**
     * @overrides Account.php
     * @return int type
     */
    public function getType() {
        return 1;
    }
}