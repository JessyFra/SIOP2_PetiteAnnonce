<?php

class PrivateMessagesDTO
{

    private $user_id;
    private $recipient_id;

    public function __construct($user_id, $recipient_id)
    {
        $this->user_id = $user_id;
        $this->recipient_id = $recipient_id;
    }

    // Getters et Setters
    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($id)
    {
        $this->user_id = $id;
    }

    public function getRecipientId()
    {
        return $this->recipient_id;
    }

    public function setRecipientId($recipient_id)
    {
        $this->recipient_id = $recipient_id;
    }

    // Autres fonctions
    public function getUser() {
        $user = UserDAO::get($this->user_id);
        return $user;
    }

    public function getRecipient() {
        $user = UserDAO::get($this->recipient_id);
        return $user;
    }

}
