<?php

namespace AppBundle\Model;

class Box {

    public $index;
    public $valeur;
    public $liste_players = [];

    public function __construct($index, $valeur) {
        $this->index = $index;
        $this->valeur = $valeur;
    }

}
