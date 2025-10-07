<?php

include_once 'private/src/model/DAO/AnnounceDAO.php';
include_once 'private/src/model/DAO/CategoryDAO.php';

class AnnounceCategoryDTO {

    private $announce_id;
    private $category_id;

    public function __construct($announce_id, $category_id) {
        $this->announce_id = $announce_id;
        $this->category_id = $category_id;
    }

    // Getters et Setters
    public function getAnnounceId() {
        return $this->announce_id;
    }
    public function setAnnounceId($id) {
        $this->announce_id = $id;
    }

    public function getCategoryId() {
        return $this->category_id;
    }
    public function setCategoryId($category_id) {
        $this->category_id = $category_id;
    }

    // Autres fonctions
    public function getAnnounceTitle() {
        $announce = AnnounceDAO::get($this->announce_id);
        return $announce->getTitle();
    }

    public function getCategoryName() {
        $category = CategoryDAO::get($this->category_id);
        return $category->getName();
    }
}
