<?php

class Theme {

    private $name;
    private $imgLink;

    public function __construct($name, $imgLink) {
        $this->name = $name;
        $this->imgLink = $imgLink;
    }

    /**
     * Get the name of the Theme
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get the link to the image
     * @return string
     */
    public function getImageLink() {
        return $this->imgLink;
    }
}