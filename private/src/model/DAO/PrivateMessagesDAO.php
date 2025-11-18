<?php

require_once 'private/config/DataBaseLinker.php';
require_once 'private/src/model/DTO/PrivateMessagesDTO.php';

class PrivateMessagesDAO
{
    public static function getAll($userId)
    {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("SELECT * FROM private_messages WHERE user_id = ?");
        $query->execute(array($userId));
        $results = $query->fetchAll();

        $privates_messages = [];

        foreach ($results as $result) {
            $private_messages = new PrivateMessagesDTO(
                $result["user_id"],
                $result["recipient_id"]
            );

            $privates_messages[] = $private_messages;
        }

        return $privates_messages;
    }

    public static function create($userId, $recipientId)
    {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("INSERT INTO private_messages (user_id, recipient_id) VALUES (?, ?)");
        $query->execute(array($userId, $recipientId));

    }

    public static function delete($userId, $recipientId)
    {
        $bdd = DatabaseLinker::getConnexion();

        $query = $bdd->prepare("DELETE FROM private_messages WHERE user_id = ? AND recipient_id = ?");
        $query->execute(array($userId, $recipientId));

    }

    public static function deleteOldRecipientId($userId, $recipientId)
    {
        $bdd = DatabaseLinker::getConnexion();

        $check = $bdd->prepare("SELECT COUNT(*) FROM private_messages WHERE user_id = ? AND recipient_id = ?");
        $check->execute(array($userId, $recipientId));
        $count = $check->fetchColumn();

        if ($count <= 1) {
            return false;
        }

        $query = $bdd->prepare("
            DELETE FROM private_messages
            WHERE id = (
                SELECT id FROM private_messages
                WHERE user_id = ? AND recipient_id = ?
                ORDER BY id
                LIMIT 1
            )
        ");

        $query->execute(array($userId, $recipientId));

        return true;
    }

}
