<?php

include_once 'private/src/model/DAO/UserDAO.php';
include_once 'private/src/model/DTO/UserDTO.php';

class UserControl
{
    public function auth()
    {
        $userDAO = new UserDAO();

        // Connexion
        if (isset($_POST['connexion'])) {
            $name = $_POST['username'];
            $password = $_POST['password'];

            $userData = $userDAO->getUserInfo($name, $password);

            if ($userData) {
                $user = new UserDTO();
                $user->setId($userData['id']);
                $user->setName($userData['name']);
                $user->setHashedPassword($userData['hashed_password']);
                $user->setGlobalName($userData['global_name']);
                $user->setBiography($userData['biography']);
                $user->setRole($userData['role']);
                $user->setCreatedAt($userData['created_at']);

                $_SESSION['userID'] = $user->getId();
                $_SESSION['username'] = $user->getName();

                header('Location: index.php?page=annonces');
                exit;
            } else {
                echo "Identifiants incorrects.";
            }
        }

        if (isset($_POST['inscription'])) {
            $name = $_POST['username'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            // Vérification des mots de passe
            if ($password !== $confirmPassword) {
                echo "Les mots de passe ne correspondent pas.";
                return;
            }

            // Vérification du login existant
            if ($userDAO->getLoginExist($name)) {
                echo "Ce nom d'utilisateur est déjà pris.";
                return;
            }

            // Insertion en base
            $userDAO->insertUser($name, $password);
            echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
        }


        // Vue du formulaire
        include_once 'private/src/view/auth.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?page=auth');
        exit;
    }
}
