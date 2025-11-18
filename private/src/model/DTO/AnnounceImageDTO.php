<?php

class AnnounceImageDTO
{
    private $id;
    private $announce_id;
    private $image_path;
    private $is_main;
    private $created_at;

    public function __construct($id, $announce_id, $image_path, $is_main, $created_at)
    {
        $this->id = $id;
        $this->announce_id = $announce_id;
        $this->image_path = $image_path;
        $this->is_main = $is_main;
        $this->created_at = $created_at;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getAnnounceId()
    {
        return $this->announce_id;
    }

    public function getImagePath()
    {
        return $this->image_path;
    }

    public function isMain()
    {
        return $this->is_main == 1;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setAnnounceId($announce_id)
    {
        $this->announce_id = $announce_id;
    }

    public function setImagePath($image_path)
    {
        $this->image_path = $image_path;
    }

    public function setIsMain($is_main)
    {
        $this->is_main = $is_main;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    // Autres méthodes
    public function getFullPath()
    {
        return "public/assets/img/" . $this->image_path;
    }
}
