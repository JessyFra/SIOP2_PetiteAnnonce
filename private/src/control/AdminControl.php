<?php

include_once 'private/src/model/DAO/UserDAO.php';
include_once 'private/src/model/DAO/AnnounceDAO.php';

class AdminControl
{
    // Vérifie si l'utilisateur connecté est admin
    private function checkAdmin()
    {
        if (!isset($_SESSION['userID'])) {
            header("Location: index.php?page=auth");
            exit;
        }

        $userDAO = new UserDAO();
        $user = $userDAO->get($_SESSION['userID']);

        if (!$user || $user->getRole() !== 'admin') {
            header("Location: index.php?page=annonces");
            exit;
        }

        return $user;
    }

    // Page principale de l'administration
    public function dashboard()
    {
        $admin = $this->checkAdmin();
        $message = null;

        // Récupération des statistiques
        $userDAO = new UserDAO();
        $announceDAO = new AnnounceDAO();

        $totalUsers = UserDAO::count();
        $bannedUsers = UserDAO::countBanned();
        $allAnnounces = AnnounceDAO::getAll();
        $totalAnnounces = count($allAnnounces);
        $openAnnounces = count(array_filter($allAnnounces, fn($a) => $a->getStatus() == 'open'));

        include_once 'private/src/view/admin/dashboard.php';
    }

    // Gestion des utilisateurs
    public function manageUsers()
    {
        $admin = $this->checkAdmin();
        $message = null;
        $userDAO = new UserDAO();

        // Bannir/Débannir un utilisateur
        if (isset($_POST['toggleBan'])) {
            $userId = intval($_POST['userId']);
            $currentStatus = intval($_POST['currentBanned']);

            // Empêcher de bannir un admin
            $targetUser = $userDAO->get($userId);
            if ($targetUser && $targetUser->getRole() !== 'admin') {
                $newStatus = $currentStatus == 1 ? 0 : 1;
                if ($userDAO->toggleBan($userId, $newStatus)) {
                    $message = $newStatus == 1 ? "Utilisateur banni avec succès." : "Utilisateur débanni avec succès.";
                } else {
                    $message = "Erreur lors de la modification du statut.";
                }
            } else {
                $message = "Impossible de bannir un administrateur.";
            }
        }

        // Supprimer un utilisateur
        if (isset($_POST['deleteUser'])) {
            $userId = intval($_POST['userId']);

            // Empêcher de supprimer un admin
            $targetUser = $userDAO->get($userId);
            if ($targetUser && $targetUser->getRole() !== 'admin') {
                if ($userDAO->deleteUser($userId)) {
                    $message = "Utilisateur supprimé avec succès.";
                } else {
                    $message = "Erreur lors de la suppression.";
                }
            } else {
                $message = "Impossible de supprimer un administrateur.";
            }
        }

        $users = $userDAO->getAll();

        include_once 'private/src/view/admin/users.php';
    }

    // Gestion des annonces
    public function manageAnnounces()
    {
        $admin = $this->checkAdmin();
        $message = null;
        $announceDAO = new AnnounceDAO();

        // Clôturer une annonce
        if (isset($_POST['closeAnnounce'])) {
            $announceId = intval($_POST['announceId']);
            if (AnnounceDAO::updateStatus($announceId, 'closed')) {
                $message = "Annonce clôturée avec succès.";
            } else {
                $message = "Erreur lors de la clôture.";
            }
        }

        // Rouvrir une annonce
        if (isset($_POST['reopenAnnounce'])) {
            $announceId = intval($_POST['announceId']);
            if (AnnounceDAO::updateStatus($announceId, 'open')) {
                $message = "Annonce rouverte avec succès.";
            } else {
                $message = "Erreur lors de la réouverture.";
            }
        }

        // Supprimer une annonce
        if (isset($_POST['deleteAnnounce'])) {
            $announceId = intval($_POST['announceId']);
            if (AnnounceDAO::delete($announceId)) {
                $message = "Annonce supprimée avec succès.";
            } else {
                $message = "Erreur lors de la suppression.";
            }
        }

        // Récupération des annonces avec filtres
        $filters = [];
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (!empty($_GET['type'])) {
            $filters['type'] = $_GET['type'];
        }

        if (!empty($filters)) {
            $announces = AnnounceDAO::search($filters);
        } else {
            $announces = AnnounceDAO::search(['status' => '']); // Toutes les annonces
        }

        include_once 'private/src/view/admin/announces.php';
    }
}
