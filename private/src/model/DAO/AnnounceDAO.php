<?php

require_once 'private/config/DatabaseLinker.php';
require_once 'private/src/model/DTO/AnnounceDTO.php';

class AnnounceDAO {

    public static function get($id) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM announce WHERE id = ?");
        $query->execute(array($id));
        $result = $query->fetch();

        if ($result != NULL) {
            $announce = new AnnounceDTO(
                $result["id"],
                $result["title"],
                $result["description"],
                $result["price"],
                $result["city_id"],
                $result["author_id"],
                $result["created_at"]
            );
        }

        return null;
}

    public static function getAll() {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query("SELECT * FROM announce");
        $results = $query->fetchAll();

        $announces = [];

        foreach ($results as $result) {
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

        return $announces;
    }

}