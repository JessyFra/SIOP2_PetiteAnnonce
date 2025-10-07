<?php

require_once 'private/config/DataBaseLinker.php';
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

            return $user;
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

    public function getLoginExist($name)
    {
        $bdd = DatabaseLinker::getConnexion();

        // Vérification du login si existant
        $state = $bdd->prepare("SELECT name FROM user WHERE name = ?");
        $state->execute(array($name));
        return $state->fetch();
    }

    public function getUserInfo($name, $password)
    {
        $bdd = DatabaseLinker::getConnexion();

        $state = $bdd->prepare("SELECT * FROM user WHERE name = ? AND hashed_password = SHA2(?, 256)");
        $state->execute(array($name, $password));
        return $state->fetch();
    }

    public function insertUser($name, $password)
    {
        $bdd = DatabaseLinker::getConnexion();

        $state = $bdd->prepare("INSERT INTO user (name, hashed_password) VALUES (?, SHA2(?, 256))");
        $state->execute(array($name, $password));
    }

    public function updateUser(UserDTO $user, $newPassword = null)
    {
        $bdd = DatabaseLinker::getConnexion();

        if ($newPassword) {
            $state = $bdd->prepare("
            UPDATE user 
            SET global_name = ?, biography = ?, name = ?, hashed_password = SHA2(?, 256)
            WHERE id = ?
        ");
            $state->execute([
                $user->getGlobalName(),
                $user->getBiography(),
                $user->getName(),
                $newPassword,
                $user->getId()
            ]);
        } else {
            $state = $bdd->prepare("
            UPDATE user 
            SET global_name = ?, biography = ?, name = ?
            WHERE id = ?
        ");
            $state->execute([
                $user->getGlobalName(),
                $user->getBiography(),
                $user->getName(),
                $user->getId()
            ]);
        }
    }


    public function deleteUser($id)
    {
        $bdd = DatabaseLinker::getConnexion();

        $state = $bdd->prepare("DELETE FROM user WHERE id = ?");
        $state->execute([$id]);
    }
}
