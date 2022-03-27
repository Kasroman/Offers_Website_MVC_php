<?php

namespace Kasroman\Models;

use Kasroman\Core\Db;

class Model extends Db{

    // Table de la bdd
    protected $table;

    // Instance de Db
    private $db;


    //----------------------CREATE-------------------------------------------------------------------------------------
    public function create(){
        $fields = [];
        $questMarks = [];
        $values = [];

        // On éclate le tableau
        foreach($this as $field => $value){
            // On vérifie que la valeur donnée n'est pas nule
            // On n'ajoute pas d'element au tableau pour les propriétés du parent Model du modèle donné en paramètre à la méthode
            // Donc pour 'db' et 'table'
            if($value !== null && $field !== 'db' && $field !== 'table'){
                $fields[] = $field;
                $questMarks[] = '?';
                $values[] = $value;
            }
        }

        // On transforme les tableau $fields et $questMarks_list en une chaine de caractères
        $fields_list = implode(', ', $fields);
        $questMarks_list = implode(', ', $questMarks);

        // On execute la requête
        return $this->mQuery('INSERT INTO ' . $this->table . ' (' . $fields_list . ') VALUES (' . $questMarks_list . ')', $values);
    }
    //-----------------------------------------------------------------------------------------------------------------

    //----------------------READ---------------------------------------------------------------------------------------
    public function getAll(){
        $query = $this->mQuery('SELECT * FROM ' . $this->table);
        return $query->fetchAll();
    }

    // Sous la forme exemple : getBy(['id' => 3, 'title' => 'Annonce 3']
    public function getBy(array $crits){
        $fields = [];
        $values = [];

        // On éclate le tableau
        foreach($crits as $field => $value){
            $fields[] = "$field = ?";
            $values[] = $value;
        }

        // On transforme le tableau $fields en une chaine de caractères
        $fields_list = implode(' AND ', $fields);

        // On execute la requête
        return $this->mQuery('SELECT * FROM ' . $this->table . ' WHERE ' . $fields_list, $values)->fetchAll();
        
    }

    public function get(int $id){
        return $this->mQuery("SELECT * FROM $this->table WHERE id = $id")->fetch();
    }
    //-----------------------------------------------------------------------------------------------------------------

    //----------------------UPDATE-------------------------------------------------------------------------------------
    public function update(){
        $fields = [];
        $values = [];

        // On éclate le tableau
        foreach($this as $field => $value){
            // On vérifie que la valeur donnée n'est pas nule
            // On n'ajoute pas d'element au tableau pour les propriétés du parent Model du modèle donné en paramètre à la méthode
            // Donc pour 'db' et 'table'
            if($value !== null && $field !== 'db' && $field !== 'table'){
                $fields[] = "$field = ?";
                $values[] = $value;
            }
        }
        $values[] = $this->id;

        // On transforme le tableau $fields en une chaine de caractères
        $fields_list = implode(', ', $fields);

        // On execute la requête
        return $this->mQuery('UPDATE ' . $this->table . ' SET ' . $fields_list . ' WHERE id = ?', $values);
    }
    //-----------------------------------------------------------------------------------------------------------------

    //----------------------DELETE-------------------------------------------------------------------------------------
    public function delete(int $id){
        return $this->mQuery("DELETE FROM {$this->table} WHERE id = ?", [$id]);
        // return $this->mQuery("DELETE FROM $this->table WHERE id = $id");
    }
    //-----------------------------------------------------------------------------------------------------------------

    // ****************************************************************************************************************

    //----------------------QUERY--------------------------------------------------------------------------------------
    public function mQuery(string $sql, array $attributs = null){

        // On récupère l'instance de Db
        $this->db = Db::getInstance();

        // On vérifie l'existance d'attributs
        if($attributs !== null){

            // Requête préparé
            $query = $this->db->prepare($sql);
            $query->execute($attributs);
            return $query;
        }else{

            // Requête simple
            return $this->db->query($sql);
        }
    }
    //-----------------------------------------------------------------------------------------------------------------

    //----------------------HYDRATE------------------------------------------------------------------------------------
    public function hydrate($data){
        foreach($data as $key => $value){

            // On récupère le nom du setter correspondant à la clé exemple : title --> setTitle
            $setter = 'set' . ucfirst($key);

            // On vérifie si le setter existe
            if(method_exists($this, $setter)){

                // On appelle le setter exemple : ici $setter = 'setTitle'
                $this->$setter($value);
            }
        }
        return $this;
    }
    //-----------------------------------------------------------------------------------------------------------------
}



