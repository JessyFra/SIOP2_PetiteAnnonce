<?php

require_once 'private/config/DataBaseLinker.php';
require_once 'private/src/model/DTO/CategoryDTO.php';

class CategoryDAO {

    public static function get($id) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM category WHERE id = ?");
        $query->execute(array($id));
        $result = $query->fetch();

        if ($result != NULL) {
            $category = new CategoryDTO(
                $result["id"],
                $result["name"]
            );

            return $category;
        }

        return null;
    }

    public static function getAll() {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query("SELECT * FROM category");
        $results = $query->fetchAll();

        $categories = [];

        foreach ($results as $result) {
            $category = new CategoryDTO(
                $result["id"],
                $result["name"]
            );

            $categories[] = $category;
        }

        foreach ($categories as $key => $category) {
            if ($category->getId() == 1) {
                $otherCategory = $category;
                unset($categories[$key]);
                $categories[] = $otherCategory;
                break;
            }
        }

        return $categories;
    }

}