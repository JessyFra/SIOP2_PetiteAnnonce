<?php

include_once 'private/config/DataBaseLinker.php';

class AuthUserModel
{

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
}
