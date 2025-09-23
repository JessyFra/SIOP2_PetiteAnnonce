<?php

include_once 'private\src\model\DAO\AnnounceCategoryDAO.php';
include_once 'private\src\model\DAO\Announce.php';

class CategoryDTO {

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
    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }

    // Autres fonctions
    public function getAnnounces() {

        $results = AnnounceCategoryDAO::getAll();
        $announces = [];

        foreach ($results as $result) {
            if ($result["category_id"] == $this->id) {
                $announce = AnnounceDAO::get($result["announce_id"]);

                $announce = new AnnounceDTO(
                    $result["id"],
                    $result["title"],
                    $result["description"],
                    $result["price"],
                    $result["city_id"],
                    $result["author_id"],
                    $result["created_at"]
                );

                $announces[] = $announce;
            }
        }

        return $announces;
    }
    
}
