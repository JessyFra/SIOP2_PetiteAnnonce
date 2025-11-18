<?php

include_once 'private/src/model/DAO/AnnounceCategoryDAO.php';
include_once 'private/src/model/DAO/AnnounceDAO.php';

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
    public function getAnnounces()
    {
        $results = AnnounceCategoryDAO::getAll();
        $announces = [];

        foreach ($results as $result) {
            if ($result->getCategoryId() == $this->id) {
                $announce = AnnounceDAO::get($result->getAnnounceId());
                $announceDTO = new AnnounceDTO(
                    $announce->getId(),
                    $announce->getTitle(),
                    $announce->getDescription(),
                    $announce->getPrice(),
                    $announce->getStatus(),
                    $announce->getType(),
                    $announce->getCityId(),
                    $announce->getAuthorId(),
                    $announce->getCreatedAt()
                );
                $announces[] = $announceDTO;
            }
        }

        // Trier les annonces par date de création décroissante
        usort($announces, function ($a, $b) {
            return strtotime($b->getCreatedAt()) - strtotime($a->getCreatedAt());
        });

        return $announces;
    }
    
}
