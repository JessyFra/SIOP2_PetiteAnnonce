<?php

class CityDTO {
    
    private $id;
    private $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    // Getters et Setters
    public function getId() {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }

    // Autres fonctions

}