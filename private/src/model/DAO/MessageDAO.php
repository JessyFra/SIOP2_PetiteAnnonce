<?php

require_once 'private/config/DataBaseLinker.php';
require_once 'private/src/model/DTO/MessageDTO.php';

class MessageDAO {

    public static function get($id) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM message WHERE id = ?");
        $query->execute(array($id));
        $result = $query->fetch();

        if ($result != NULL) {
            $message = new MessageDTO(
                $result["id"],
                $result["content"],
                $result["author_id"],
                $result["recipient_id"],
                $result["created_at"]
            );

            return $message;
        }

        return null;
    }

    public static function getAll($authorId, $recipientId) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM message WHERE (author_id = ? AND recipient_id = ?) OR (recipient_id = ? AND author_id = ?) ORDER BY created_at");
        $query->execute(array($authorId, $recipientId, $authorId, $recipientId));
        $results = $query->fetchAll();

        $messages = [];

        foreach ($results as $result) {
            $message = new MessageDTO(
                $result["id"],
                $result["content"],
                $result["author_id"],
                $result["recipient_id"],
                $result["created_at"]
            );

            $messages[] = $message;
        }

        return $messages;
    }

    public static function insert($content, $authorId, $recipientId) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("INSERT INTO message (content, author_id, recipient_id) VALUES (?, ?, ?)");
        $query->execute(array($content, $authorId, $recipientId));
    }

    public static function getCountMessages($authorId, $recipientId) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT COUNT(*) AS count FROM message WHERE (author_id = ? AND recipient_id = ?) OR (author_id = ? AND recipient_id = ?)");
        $query->execute(array($authorId, $recipientId, $recipientId, $authorId));
        $result = $query->fetch();

        return $result['count'];
    }

    public static function getLastMessage($authorId, $recipientId) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM message WHERE (author_id = ? AND recipient_id = ?) OR (author_id = ? AND recipient_id = ?) ORDER BY created_at DESC LIMIT 1");
        $query->execute(array($authorId, $recipientId, $recipientId, $authorId));
        $result = $query->fetch();

        return $result;
    }

}