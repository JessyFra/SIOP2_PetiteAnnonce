<?php

require_once 'private/config/DatabaseLinker.php';
require_once 'private/src/model/DTO/UserDTO.php';

class UserDAO {

    public static function get($id) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM user WHERE id = ?");
        $query->execute(array($id));
        $result = $query->fetch();

        if ($result != NULL) {
            $user = new UserDTO(
                $result["id"],
                $result["name"],
                $result["hashed_password"],
                $result["global_name"],
                $result["biography"],
                $result["role"],
                $result["created_at"]
            );
        }

        return null;
    }

    public static function getAll() {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query("SELECT * FROM user");
        $results = $query->fetchAll();

        $users = [];

        foreach ($results as $result) {
            $user = new UserDTO(
                $result["id"],
                $result["name"],
                $result["hashed_password"],
                $result["global_name"],
                $result["biography"],
                $result["role"],
                $result["created_at"]
            );

            $users[] = $user;
        }

        return $users;
    }

}