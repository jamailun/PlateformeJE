<?php


interface Account {
    /**
     * @return string
     */
    public function getNom();

    /**
     * @return int
     */
    public function getID();

    /**
     * @return int
     */
    public function getType();
}