<?php

include_once 'private/src/model/DAO/UserDAO.php';
include_once 'private/src/model/DTO/UserDTO.php';

class UserControl
{
    public function auth()
    {
        $userDAO = new UserDAO();

        $message = null;

        // Connexion
        if (isset($_POST['connexion'])) {
            $name = $_POST['username'];
            $password = $_POST['password'];

            $userData = $userDAO->getUserInfo($name, $password);

            if ($userData) {
                $user = new UserDTO(
                    $userData['id'],
                    $userData['name'],
                    $userData['hashed_password'],
                    $userData['global_name'],
                    $userData['biography'],
                    $userData['role'],
                    $userData['created_at']
                );

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
                $message = "Connexion -> Identifiants incorrects";
            }
        }

        // Inscription
        if (isset($_POST['inscription'])) {
            $name = $_POST['username'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($password !== $confirmPassword) {
                $message = "Inscription -> Les mots de passe ne correspondent pas.";
            } elseif ($userDAO->getLoginExist($name)) {
                $message = "Inscription -> Ce nom d'utilisateur est déjà pris.";
            } else {
                $userDAO->insertUser($name, $password);
                $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            }
        }


        // Vue du formulaire
        include_once 'private/src/view/auth.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?page=annonces');
        exit;
    }

    public function profil()
    {
        if (!isset($_SESSION['userID'])) {
            header("Location: index.php?page=auth");
            exit;
        }

        $userDAO = new UserDAO();
        $user = $userDAO->get($_SESSION['userID']);
        $message = null;

        // Mise à jour profil
        if (isset($_POST['updateProfile'])) {
            $user->setName($_POST['name']);
            $user->setGlobalName($_POST['global_name']);
            $user->setBiography($_POST['biography']);
            $newPassword = $_POST['new_password'];

            if ($message === null) {
                $userDAO->updateUser($user, $newPassword);
                $message = "Profil mis à jour avec succès.";
            }
        }


        // Suppression compte
        if (isset($_POST['deleteAccount'])) {
            $userDAO->deleteUser($user->getId());
            session_unset();
            session_destroy();
            header("Location: index.php?page=annonces");
            exit;
        }

        include_once 'private/src/view/profilUser.php';
    }
}
