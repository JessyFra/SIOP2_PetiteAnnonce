<?php

require_once 'private/config/DatabaseLinker.php';
require_once 'private/src/model/DTO/CityDTO.php';

class CityDAO {

    public static function get($id) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM city WHERE id = ?");
        $query->execute(array($id));
        $result = $query->fetch();

        if ($result != NULL) {
            $city = new CityDTO(
                $result["id"],
                $result["name"]
            );

            return $city;
        }

        return null;
    }

    public static function getAll() {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query("SELECT * FROM city");
        $results = $query->fetchAll();

        $cities = [];

        foreach ($results as $result) {
            $city = new CityDTO(
                $result["id"],
                $result["name"]
            );

            $cities[] = $city;
        }

        return $cities;
    }

}