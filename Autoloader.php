<?php

namespace Kasroman;

class Autoloader {
    static function register(){

        // spl_autoload_register() permet une détection automatique des instanciations de classe ('new') et qui execute une méthode choisie de notre classe
        // En l'occurence elle va charger autoload()
        spl_autoload_register([
            // __CLASS__ Renvoie la totalité du namespace dans lequel la classe est utilisé. Exemple : Kasroman\classes\Class
            __CLASS__,
            'autoload'
        ]);
    }

    static function autoload($class){

        // On remplace le nom du namespace et '\' par rien. Exemple : Kasroman\classes\Class --> classes\Class
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);

        // On remplace les '\' par des '/'. Exemple : classes\Class --> classes/Class
        $class = str_replace('\\', '/', $class);

        // __DIR__ renvoie le chemin d'accès au dossier. Exemple : ici on aura : C:\wamp64\www\POO\classes/Class.php
        $file = __DIR__ . '/' . $class . '.php';

        // On vérifie que le fichier existe avant de le charger
        if(file_exists($file)){
            require_once $file;
        }
    }
}