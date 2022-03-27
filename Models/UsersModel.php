<?php

namespace Kasroman\Models;

class UsersModel extends Model{
    protected $id;
    protected $email;
    protected $password;
    protected $roles;

    public function __construct()
    {
        // Juste pour le fun on récupère le nom de la table par le nom du modèle
        // au lieu de simplement ecrire  : $this->table = 'users'; \(°_^)/
        $class = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
        $this->table = strtolower(str_replace('Model', '', $class));
    }

    /**
     * Créer la session de l'utilisateur dans les cookies
     *
     * @return void
     */
    public function setSession(){
        $_SESSION['user'] = [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles
        ];
    }

    /**
     * Récuprer user depuis un email
     *
     * @param string $email
     * @return mixed
     */
    public function getOneByEmail(string $email){
        return $this ->mQuery("SELECT * FROM {$this->table} WHERE email = ?", [$email])->fetch();
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of roles
     */ 
    public function getRoles():array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = json_decode($roles);

        return $this;
    }
}