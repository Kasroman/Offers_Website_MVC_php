<?php

namespace Kasroman\Core;

// On importe PDO
use PDO, PDOException;

class Db extends PDO{

    // Instance unique de la classe
    private static $instance;

    // Informations de connexion
    private const DB_HOST = 'localhost';
    private const DB_PORT = '3307';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_NAME = 'site_annonces';

    // Constructeur privé qu'on ne peut pas instancier
    private function __construct(){
        // DSN  de connexion
        $_dsn ='mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_HOST . ';port=' . self::DB_PORT;

        // On appelle le consutructeur de PDO
        try{
            parent::__construct($_dsn, self::DB_USER, self::DB_PASS);

            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');

            // On utilise un FETCH_OBJ pour utiliser les objets dans les vues
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    // Méthode qui permet de générer une instance ou de la récupérer si elle existe déjà
    public static function getInstance(){
        if(self::$instance === null){

            // On aurait pu mettre new Database ou new PDO
            self::$instance = new self();
        }
        return self::$instance;
    }
}