<?php


class Formation {

    // Link to the video
    private $videoLink;

    // Number of times the video has been viewed
    private $viewsCount;

    // Displayed name of the video
    private $name;

    // Id of the account who published the video
    private $idAccount;

    // Id of this formation
    private $id;

    private $description;

    /**
     * Formation constructor.
     * @param $id
     * @param $name
     * @param $idAccount
     * @param $videoLink
     */
    public function __construct($id, $name/*, $idAccount, $videoLink*/) {
        $this->id = $id;
        $this->idAccount = /*$idAccount;*/ $id."_acc";
        $this->name = $name;
        $this->videoLink = /*$videoLink;*/ $id."_link";
        $this->viewsCount = 0;
    }

    public function getVideoLink() {
        return $this->videoLink;
    }

    public function getViewsCount() {
        return $this->viewsCount;
    }

    public function getVideoName() {
        return $this->name;
    }

    public function getOwnerAccountId() {
        return $this->idAccount;
    }

    public function getDescription() {
        return "Formation de ".$this->getOwnerAccountId();
    }

}