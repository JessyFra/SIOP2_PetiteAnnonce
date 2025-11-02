<?php

require_once 'private/config/DataBaseLinker.php';
require_once 'private/src/model/DTO/AnnounceDTO.php';

class AnnounceDAO
{

    public static function get($id)
    {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM announce WHERE id = ?");
        $query->execute(array($id));
        $result = $query->fetch();

        if ($result != NULL) {
            $announce = new AnnounceDTO(
                $result["id"],
                $result["title"],
                $result["description"],
                $result["price"],
                $result["status"],
                $result["type"],
                $result["city_id"],
                $result["author_id"],
                $result["created_at"]
            );

            return $announce;
        }

        return null;
    }

    public static function getAll()
    {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query("SELECT * FROM announce");
        $results = $query->fetchAll();

        $announces = [];

        foreach ($results as $result) {
            $announce = new AnnounceDTO(
                $result["id"],
                $result["title"],
                $result["description"],
                $result["price"],
                $result["status"],
                $result["type"],
                $result["city_id"],
                $result["author_id"],
                $result["created_at"]
            );

            $announces[] = $announce;
        }

        return $announces;
    }

    public static function getByUser($userId)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("SELECT * FROM announce WHERE author_id = ? ORDER BY created_at DESC");
        $query->execute([$userId]);
        $results = $query->fetchAll();

        $announces = [];
        foreach ($results as $result) {
            $announces[] = new AnnounceDTO(
                $result['id'],
                $result['title'],
                $result['description'],
                $result['price'],
                $result['status'],
                $result['type'],
                $result['city_id'],
                $result['author_id'],
                $result['created_at']
            );
        }
        return $announces;
    }

    /**
     * Recherche d'annonces avec filtres
     * @param array $filters Tableau associatif avec les clés : category_id, city_id, type, status, search
     * @return array Tableau d'objets AnnounceDTO
     */
    public static function search($filters = [])
    {
        $bdd = DatabaseLinker::getConnexion();

        $sql = "SELECT DISTINCT a.* FROM announce a";
        $conditions = [];
        $params = [];

        // Jointure si recherche par catégorie
        if (!empty($filters['category_id'])) {
            $sql .= " INNER JOIN announce_category ac ON a.id = ac.announce_id";
            $conditions[] = "ac.category_id = ?";
            $params[] = $filters['category_id'];
        }

        // Filtre par ville
        if (!empty($filters['city_id'])) {
            $conditions[] = "a.city_id = ?";
            $params[] = $filters['city_id'];
        }

        // Filtre par type (offre/demande)
        if (!empty($filters['type'])) {
            $conditions[] = "a.type = ?";
            $params[] = $filters['type'];
        }

        // Filtre par statut
        if (!empty($filters['status'])) {
            $conditions[] = "a.status = ?";
            $params[] = $filters['status'];
        } else {
            // Par défaut, afficher seulement les annonces ouvertes
            $conditions[] = "a.status = 'open'";
        }

        // Recherche par mots-clés (titre ou description)
        if (!empty($filters['search'])) {
            $conditions[] = "(a.title LIKE ? OR a.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Assemblage de la requête
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY a.created_at DESC";

        $query = $bdd->prepare($sql);
        $query->execute($params);
        $results = $query->fetchAll();

        $announces = [];
        foreach ($results as $result) {
            $announces[] = new AnnounceDTO(
                $result['id'],
                $result['title'],
                $result['description'],
                $result['price'],
                $result['status'],
                $result['type'],
                $result['city_id'],
                $result['author_id'],
                $result['created_at']
            );
        }

        return $announces;
    }

    /**
     * Met à jour le statut d'une annonce
     */
    public static function updateStatus($announceId, $status)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("UPDATE announce SET status = ? WHERE id = ?");
        return $query->execute([$status, $announceId]);
    }

    /**
     * Supprime une annonce
     */
    public static function delete($announceId)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("DELETE FROM announce WHERE id = ?");
        return $query->execute([$announceId]);
    }
}
