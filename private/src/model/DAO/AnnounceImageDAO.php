<?php

require_once 'private/config/DataBaseLinker.php';
require_once 'private/src/model/DTO/AnnounceImageDTO.php';

class AnnounceImageDAO
{
    // Récupère une image par son ID
    public static function get($id)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("SELECT * FROM announce_image WHERE id = ?");
        $query->execute([$id]);
        $result = $query->fetch();

        if ($result) {
            return new AnnounceImageDTO(
                $result['id'],
                $result['announce_id'],
                $result['image_path'],
                $result['is_main'],
                $result['created_at']
            );
        }

        return null;
    }

    // Récupère toutes les images d'une annonce
    public static function getByAnnounce($announceId)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("SELECT * FROM announce_image WHERE announce_id = ? ORDER BY is_main DESC, created_at ASC");
        $query->execute([$announceId]);
        $results = $query->fetchAll();

        $images = [];
        foreach ($results as $result) {
            $images[] = new AnnounceImageDTO(
                $result['id'],
                $result['announce_id'],
                $result['image_path'],
                $result['is_main'],
                $result['created_at']
            );
        }

        return $images;
    }

    // Récupère l'image principale d'une annonce
    public static function getMainImage($announceId)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("SELECT * FROM announce_image WHERE announce_id = ? AND is_main = 1 LIMIT 1");
        $query->execute([$announceId]);
        $result = $query->fetch();

        if ($result) {
            return new AnnounceImageDTO(
                $result['id'],
                $result['announce_id'],
                $result['image_path'],
                $result['is_main'],
                $result['created_at']
            );
        }

        return null;
    }

    // Insère une nouvelle image
    public static function insert($announceId, $imagePath, $isMain = 0)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("INSERT INTO announce_image (announce_id, image_path, is_main) VALUES (?, ?, ?)");
        return $query->execute([$announceId, $imagePath, $isMain]);
    }

    // Définit une image comme principale (et retire ce statut aux autres)
    public static function setAsMain($imageId, $announceId)
    {
        $bdd = DatabaseLinker::getConnexion();

        // Retirer le statut principal des autres images
        $query1 = $bdd->prepare("UPDATE announce_image SET is_main = 0 WHERE announce_id = ?");
        $query1->execute([$announceId]);

        // Définir cette image comme principale
        $query2 = $bdd->prepare("UPDATE announce_image SET is_main = 1 WHERE id = ?");
        return $query2->execute([$imageId]);
    }

    // Supprime une image
    public static function delete($id)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("DELETE FROM announce_image WHERE id = ?");
        return $query->execute([$id]);
    }

    // Supprime toutes les images d'une annonce
    public static function deleteByAnnounce($announceId)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("DELETE FROM announce_image WHERE announce_id = ?");
        return $query->execute([$announceId]);
    }

    // Compte le nombre d'images d'une annonce
    public static function countByAnnounce($announceId)
    {
        $bdd = DatabaseLinker::getConnexion();
        $query = $bdd->prepare("SELECT COUNT(*) as total FROM announce_image WHERE announce_id = ?");
        $query->execute([$announceId]);
        $result = $query->fetch();
        return $result['total'];
    }
}
