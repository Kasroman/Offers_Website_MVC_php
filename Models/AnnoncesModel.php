<?php

namespace Kasroman\Models;

class AnnoncesModel extends Model{

    protected $id;
    protected $title;
    protected $description;
    protected $created_at;
    protected $is_active;
    protected $users_id;

    public function __construct()
    {
        $this->table = 'annonces';
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    public function getCreated_at(){
        return $this->created_at;
    }

    public function setCreated_at($created_at){
        $this->created_at = $created_at;
        return $this;
    }

    public function getIs_active(){
        return $this->is_active;
    }

    public function setIs_active($is_active){
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * Get the value of users_id
     */ 
    public function getUsers_id():int
    {
        return $this->users_id;
    }

    /**
     * Set the value of users_id
     *
     * @return  self
     */ 
    public function setUsers_id(int $users_id)
    {
        $this->users_id = $users_id;

        return $this;
    }
}