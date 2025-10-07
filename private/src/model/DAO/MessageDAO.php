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

    public static function getAll() {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->query("SELECT * FROM message");
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

}