<?php

class UserDTO
{

    private $id;
    private $name;
    private $hashed_password;
    private $global_name;
    private $biography;
    private $role;
    private $created_at;
    private $banned;

    public function __construct($id, $name, $hashed_password, $global_name, $biography, $role, $created_at, $banned = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->hashed_password = $hashed_password;
        $this->global_name = $global_name;
        $this->biography = $biography;
        $this->role = $role;
        $this->created_at = $created_at;
        $this->banned = $banned;
    }

    // Getters et Setters
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getHashedPassword()
    {
        return $this->hashed_password;
    }
    public function setHashedPassword($hashed_password)
    {
        $this->hashed_password = $hashed_password;
    }

    public function getGlobalName()
    {
        return $this->global_name;
    }
    public function setGlobalName($global_name)
    {
        $this->global_name = $global_name;
    }

    public function getBiography()
    {
        return $this->biography;
    }
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    public function getRole()
    {
        return $this->role;
    }
    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getBanned()
    {
        return $this->banned;
    }
    public function setBanned($banned)
    {
        $this->banned = $banned;
    }

    // Autres fonctions
    public function isBanned()
    {
        return $this->banned == 1;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
