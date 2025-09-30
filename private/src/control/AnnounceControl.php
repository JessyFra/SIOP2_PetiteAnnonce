<?php

include_once 'private/src/model/DAO/AnnounceDAO.php';
include_once 'private/src/model/DAO/CategoryDAO.php';

class AnnounceControl {
    public function announces() {
        // Vue de la liste des annonces
        include_once 'private/src/view/announceListe.php';
    }

    public function announce() {
        // Vue de l'annonce
        include_once 'private/src/view/announce.php';
    }
}
