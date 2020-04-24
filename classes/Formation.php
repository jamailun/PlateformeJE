<?php

require 'Membre.php';

class Formation {

    // Number of times the video has been viewed
    private $views;

    // Displayed name of the video
    private $name;

    // Id of the account who published the video
    private $idAccount;

    // Id of this formation
    private $id;

    // Description
    private $description;

    /**
     * Formation's constructor.
     * @param $id string : ID of the Formation
     */
    public function __construct($id) {
        $this->id = $id;
        $this->loadData();
    }

    private function loadData() {
        $fileData = getcwd().'/data/formations/'.$this->id.'/data.xml';
        if ( file_exists($fileData) ) {
            $xml = simplexml_load_file($fileData);
            $this->name = $xml->name;
            $this->idAccount = $xml->owner;
            $this->description = $xml->description;
            $this->views = $xml->views;
        } else {
            syslog(LOG_WARNING, 'Echec lors de l\'ouverture du fichier ('.$fileData.').');
        }
    }

    public function getID() {
        return $this->id;
    }

    public function getVideoLink() {
        return './data/formations/'.$this->id.'/video.mp4';
    }

    public function getIconLink() {
        return '../data/formations/'.$this->id.'/icon.png';
    }

    public function hasIcon() {
        return file_exists($this->getIconLink());
    }

    public function getViewsCount() {
        return $this->views;
    }

    public function getVideoName() {
        return $this->name;
    }

    public function getOwnerAccountId() {
        return $this->idAccount;
    }

    public function getDescription() {
        return $this->description;
    }

    /**
     * @return Membre
     */
    public function getOwner() {
        return new Membre($this->idAccount);
    }

}