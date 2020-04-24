<?php

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
            echo '-> ('.$fileData.')';
            $xml = new SimpleXMLElement($fileData, NULL, TRUE);
            if( ! isset($xml)) echo 'Une erreur est survenue'; else echo "Données valides.";
            echo 'ayé';
            echo $xml->asXML();
            echo 'ui';
            echo '>>>name=['.$xml->formation->name."]";
            $this->name = $xml->name;
            $this->idAccount = $xml->owner;
            $this->description = $xml->description;
            $this->views = $xml->views;
        } else {
            syslog(LOG_WARNING, 'Echec lors de l\'ouverture du fichier ('.$fileData.').');
        }
    }

    public function getVideoLink() {
        return '../data/formations/'.$this->id.'/video.mp4';
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

}