<?php

require_once 'private/config/DataBaseLinker.php';
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
                $result["status"],
                $result["city_id"],
                $result["author_id"],
                $result["created_at"]
            );

            return $announce;
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
                $result["status"],
                $result["city_id"],
                $result["author_id"],
                $result["created_at"]
            );

            $announces[] = $announce;
        }

        return $announces;
    }

    public static function getByUser($userId)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("SELECT * FROM announce WHERE author_id = ? ORDER BY created_at DESC");
        $query->execute([$userId]);
        $results = $query->fetchAll();

        $announces = [];
        foreach ($results as $result) {
            $announces[] = new AnnounceDTO(
                $result['id'],
                $result['title'],
                $result['description'],
                $result['price'],
                $result['status'],
                $result['city_id'],
                $result['author_id'],
                $result['created_at']
            );
        }
        return $announces;
    }
}