<?php

include_once 'private/src/model/DAO/AnnounceDAO.php';
include_once 'private/src/model/DAO/CategoryDAO.php';
include_once 'private/src/model/DAO/CityDAO.php';

class AnnounceControl
{

    public function announces()
    {
        // Récupération des filtres depuis l'URL
        $filters = [];

        if (!empty($_GET['category'])) {
            $filters['category_id'] = intval($_GET['category']);
        }

        if (!empty($_GET['city'])) {
            $filters['city_id'] = intval($_GET['city']);
        }

        if (!empty($_GET['type'])) {
            $filters['type'] = $_GET['type'];
        }

        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }

        // Récupération des annonces filtrées
        if (!empty($filters)) {
            $announces = AnnounceDAO::search($filters);
        } else {
            $announces = AnnounceDAO::getAll();
        }

        // Récupération des catégories et villes pour les filtres
        $categories = CategoryDAO::getAll();
        $cities = CityDAO::getAll();

        // Vue de la liste des annonces
        include_once 'private/src/view/announces.php';
    }

    public function announce()
    {
        // Vue de l'annonce
        include_once 'private/src/view/announce.php';
    }
}
