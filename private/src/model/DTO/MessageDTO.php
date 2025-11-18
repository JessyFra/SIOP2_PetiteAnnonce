<?php

class MessageDTO {

    private $id;
    private $content;
    private $author_id;
    private $recipient_id;
    private $created_at;

    public function __construct($id, $content, $author_id, $recipient_id, $created_at) {
        $this->id = $id;
        $this->content = $content;
        $this->author_id = $author_id;
        $this->recipient_id = $recipient_id;
        $this->created_at = $created_at;
    }

    // Getters et Setters
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getContent() {
        return $this->content;
    }
    public function setContent($content) {
        $this->content = $content;
    }

    public function getAuthorId() {
        return $this->author_id;
    }
    public function setAuthorId($author_id) {
        $this->author_id = $author_id;
    }
    public function getRecipientId() {
        return $this->recipient_id;
    }
    public function setRecipientId($recipient_id) {
        $this->recipient_id = $recipient_id;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    // Autres fonctions
    public function getAuthorName() {
        $author = UserDAO::get($this->author_id);
        return $author->getName();
    }

    public function getRecipientName() {
        $recipient = UserDAO::get($this->recipient_id);
        return $recipient->getName();
    }

}
