<?php

class AnnounceDTO {

    private $id;
    private $title;
    private $description;
    private $price;
    private $city_id;
    private $author_id;
    private $created_at;

    public function __construct($id, $title, $description, $price, $city_id, $author_id, $created_at) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->city_id = $city_id;
        $this->author_id = $author_id;
        $this->created_at = $created_at;
    }

    // Getters et Setters
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }
    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }
    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPrice() {
        return $this->price;
    }
    public function setPrice($price) {
        $this->price = $price;
    }

    public function getCityId() {
        return $this->city_id;
    }
    public function setCityId($city_id) {
        $this->city_id = $city_id;
    }

    public function getAuthorId() {
        return $this->author_id;
    }
    public function setAuthorId($author_id) {
        $this->author_id = $author_id;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    // Autres fonctions
    public function getCityName() {
        $city = CityDAO::get($this->city_id);
        return $city->getName();
    }

    public function getAuthorName() {
        $author = UserDAO::get($this->author_id);
        return $author->getName();
    }

    public function getCategories() {
        // todo : A remplir
        return null;
    }

}
