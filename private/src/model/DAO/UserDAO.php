<?php

require_once 'private/config/DataBaseLinker.php';
require_once 'private/src/model/DTO/UserDTO.php';

class UserDAO
{

    public static function get($id)
    {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM user WHERE id = ?");
        $query->execute(array($id));
        $result = $query->fetch();

        if ($result != NULL) {
            $user = new UserDTO(
                $result["id"],
                $result["name"],
                $result["hashed_password"],
                $result["display_name"],
                $result["biography"],
                $result["role"],
                $result["created_at"],
                $result["banned"] ?? 0
            );

            return $user;
        }

        return null;
    }

    public static function getAll()
    {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query("SELECT * FROM user ORDER BY created_at DESC");
        $results = $query->fetchAll();

        $users = [];

        foreach ($results as $result) {
            $user = new UserDTO(
                $result["id"],
                $result["name"],
                $result["hashed_password"],
                $result["display_name"],
                $result["biography"],
                $result["role"],
                $result["created_at"],
                $result["banned"] ?? 0
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
            SET display_name = ?, biography = ?, name = ?, hashed_password = SHA2(?, 256)
            WHERE id = ?
        ");
            $state->execute([
                $user->getDisplayName(),
                $user->getBiography(),
                $user->getName(),
                $newPassword,
                $user->getId()
            ]);
        } else {
            $state = $bdd->prepare("
            UPDATE user 
            SET display_name = ?, biography = ?, name = ?
            WHERE id = ?
        ");
            $state->execute([
                $user->getDisplayName(),
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

    /**
     * Bannir ou débannir un utilisateur
     */
    public function toggleBan($userId, $ban = true)
    {
        $bdd = DatabaseLinker::getConnexion();
        $state = $bdd->prepare("UPDATE user SET banned = ? WHERE id = ?");
        return $state->execute([$ban ? 1 : 0, $userId]);
    }

    /**
     * Vérifie si un utilisateur est banni
     */
    public function isBanned($userId)
    {
        $bdd = DatabaseLinker::getConnexion();
        $state = $bdd->prepare("SELECT banned FROM user WHERE id = ?");
        $state->execute([$userId]);
        $result = $state->fetch();
        return $result && $result['banned'] == 1;
    }

    /**
     * Compte le nombre total d'utilisateurs
     */
    public static function count()
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->query("SELECT COUNT(*) as total FROM user");
        $result = $query->fetch();
        return $result['total'];
    }

    /**
     * Compte le nombre d'utilisateurs bannis
     */
    public static function countBanned()
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->query("SELECT COUNT(*) as total FROM user WHERE banned = 1");
        $result = $query->fetch();
        return $result['total'];
    }
}
