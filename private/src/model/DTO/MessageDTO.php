<?php

class MessageDTO {

    private $id;
    private $content;
    private $author_id;
    private $receiver_id;
    private $created_at;

    public function __construct($id, $content, $author_id, $receiver_id, $created_at) {
        $this->id = $id;
        $this->content = $content;
        $this->author_id = $author_id;
        $this->receiver_id = $receiver_id;
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
    public function getReceiverId() {
        return $this->receiver_id;
    }
    public function setReceiverId($receiver_id) {
        $this->receiver_id = $receiver_id;
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

    public function getReceiverName() {
        $receiver = UserDAO::get($this->receiver_id);
        return $receiver->getName();
    }

}
