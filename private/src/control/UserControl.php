<?php

include_once 'private/src/model/DAO/UserDAO.php';
include_once 'private/src/model/DAO/AnnounceDAO.php';
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
                // Vérifier si l'utilisateur est banni
                if ($userData['banned'] == 1) {
                    $message = "Connexion -> Votre compte a été banni. Contactez un administrateur.";
                } else {
                    $user = new UserDTO(
                        $userData['id'],
                        $userData['name'],
                        $userData['hashed_password'],
                        $userData['global_name'],
                        $userData['biography'],
                        $userData['role'],
                        $userData['created_at'],
                        $userData['banned']
                    );

                    $_SESSION['userID'] = $user->getId();
                    $_SESSION['username'] = $user->getName();

                    header('Location: index.php?page=annonces');
                    exit;
                }
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

        // Clôturer une annonce
        if (isset($_POST['closeAnnounceId'])) {
            $announceId = intval($_POST['closeAnnounceId']);
            $announce = AnnounceDAO::get($announceId);

            // Vérifier que l'annonce appartient à l'utilisateur
            if ($announce && $announce->getAuthorId() == $_SESSION['userID']) {
                if (AnnounceDAO::updateStatus($announceId, 'closed')) {
                    $message = "Annonce clôturée avec succès.";
                } else {
                    $message = "Erreur lors de la clôture.";
                }
            }
        }

        // Rouvrir une annonce
        if (isset($_POST['reopenAnnounceId'])) {
            $announceId = intval($_POST['reopenAnnounceId']);
            $announce = AnnounceDAO::get($announceId);

            // Vérifier que l'annonce appartient à l'utilisateur
            if ($announce && $announce->getAuthorId() == $_SESSION['userID']) {
                if (AnnounceDAO::updateStatus($announceId, 'open')) {
                    $message = "Annonce rouverte avec succès.";
                } else {
                    $message = "Erreur lors de la réouverture.";
                }
            }
        }

        // Supprimer une annonce
        if (isset($_POST['deleteAnnounceId'])) {
            $announceId = intval($_POST['deleteAnnounceId']);
            $announce = AnnounceDAO::get($announceId);

            // Vérifier que l'annonce appartient à l'utilisateur
            if ($announce && $announce->getAuthorId() == $_SESSION['userID']) {
                if (AnnounceDAO::delete($announceId)) {
                    $message = "Annonce supprimée avec succès.";
                } else {
                    $message = "Erreur lors de la suppression.";
                }
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

    /**
     * Affiche le profil public d'un utilisateur
     */
    public function viewProfile()
    {
        // Récupération de l'ID utilisateur depuis l'URL
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: index.php?page=annonces");
            exit;
        }

        $userId = intval($_GET['id']);
        $userDAO = new UserDAO();
        $user = $userDAO->get($userId);

        // Si l'utilisateur n'existe pas
        if (!$user) {
            header("Location: index.php?page=annonces");
            exit;
        }

        // Récupérer les annonces de cet utilisateur
        $announces = AnnounceDAO::getByUser($userId);

        // Si c'est son propre profil, rediriger vers la page profil
        if (isset($_SESSION['userID']) && $_SESSION['userID'] == $userId) {
            header("Location: index.php?page=profil");
            exit;
        }

        include_once 'private/src/view/publicProfile.php';
    }
}
