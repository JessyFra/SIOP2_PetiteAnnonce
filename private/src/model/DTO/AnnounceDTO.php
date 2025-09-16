<?php

require_once 'private/config/DatabaseLinker.php';


class AnnounceDTO {

    
    private $id;
    private $title;
    private $description;
    private $price;
    private $city_id;
    private $author_id;
    private $created_at;

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
    public static function getAllAnnounces() {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query(
            "SELECT announce.id, title, description, price, city.name AS city_name, user.name AS user_name, category.name AS category_name, announce.created_at FROM announce
            INNER JOIN announce_category ON announce_category.announce_id = announce.id
            INNER JOIN category ON category.id = announce_category.category_id
            INNER JOIN user ON user.id = announce.author_id
            INNER JOIN city ON city.id = announce.city_id
            ORDER BY category_name DESC"
        );

        return $query->fetchAll();

    }

}
