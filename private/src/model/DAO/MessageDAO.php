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
                $result["receiver_id"],
                $result["created_at"]
            );

            return $message;
        }

        return null;
    }

    public static function getAll($authorId, $receiverId) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM message WHERE (author_id = ? AND receiver_id = ?) OR (receiver_id = ? AND author_id = ?) ORDER BY created_at");
        $query->execute(array($authorId, $receiverId, $authorId, $receiverId));
        $results = $query->fetchAll();

        $messages = [];

        foreach ($results as $result) {
            $message = new MessageDTO(
                $result["id"],
                $result["content"],
                $result["author_id"],
                $result["receiver_id"],
                $result["created_at"]
            );

            $messages[] = $message;
        }

        return $messages;
    }

    public static function insert($content, $authorId, $receiverId) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("INSERT INTO message (content, author_id, receiver_id) VALUES (?, ?, ?)");
        $query->execute(array($content, $authorId, $receiverId));
    }

    public static function getCountReceiverMessages($authorId, $receiverId) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT COUNT(*) FROM message WHERE author_id = ? AND receiver_id = ? ORDER BY created_at");
        $query->execute(array($authorId, $receiverId));
        $result = $query->fetch();

        return $result;
    }

    public static function getLastReceiverMessages($authorId, $receiverId) {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM message WHERE author_id = ? AND receiver_id = ? ORDER BY created_at DESC LIMIT 1");
        $query->execute(array($authorId, $receiverId));
        $result = $query->fetch();

        return $result;
    }

}