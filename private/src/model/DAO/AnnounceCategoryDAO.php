<?php

require_once 'private/config/DatabaseLinker.php';
require_once 'private/src/model/DTO/AnnounceCategoryDTO.php';

class AnnounceCategoryDAO {

    public static function getFromAnnounceId($announce_id) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM announce_category WHERE announce_id = ?");
        $query->execute(array($announce_id));
        $result = $query->fetch();

        if ($result != NULL) {
            $announce_category = new AnnounceCategoryDTO(
                $result["announce_id"],
                $result["category_id"]
            );

            return $announce_category;
        }

        return null;
    }

    public static function getFromCategoryId($category_id) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM announce_category WHERE category_id = ?");
        $query->execute(array($category_id));
        $result = $query->fetch();

        if ($result != NULL) {
            $announce_category = new AnnounceCategoryDTO(
                $result["announce_id"],
                $result["category_id"]
            );

            return $announce_category;
        }

        return null;
    }

    public static function getAll() {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query("SELECT * FROM announce_category");
        $results = $query->fetchAll();

        $announce_categories = [];

        foreach ($results as $result) {
            $announce_category = new AnnounceCategoryDTO(
                $result["announce_id"],
                $result["category_id"]
            );

            $announce_categories[] = $announce_category;
        }

        return $announce_categories;
    }

}
