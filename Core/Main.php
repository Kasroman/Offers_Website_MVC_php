<?php

namespace Kasroman\Core;

use Kasroman\Controllers\MainController;

/**
 * Routeur Principal
 */
class Main{

    // On retire le "trading slash" éventuel de l'url
    public function start(){

        // On démarre la session php (cookies)
        session_start();

        $uri = $_SERVER['REQUEST_URI'];
        
        // On va vérifier que $uri n'est pas vide, qu'il se termine par / et que nous ne somme pas dans le dossier racine public
        // Et on considère la chaine de caractère $uri comme un tableau
        if(!empty($uri) && $uri !== '/site_annonces/public/' && $uri[-1] === '/'){
            
            // On enlève le /
            $uri = substr($uri, 0, -1); 

            // On envoie un code de redirection permanente
            http_response_code(301);

            // On redirige vers l'url sans /
            header('Location: ' . $uri);    
        }

        // On gère les paramètres d'url --> p=controleur/methode/paramètres
        // On sépare les paramètres dans un tableau
        $params = explode('/', $_GET['p']);

        if($params[0] !== ''){

            // On a au moins un element dans l'url
            // On récupère l'element, le premier est le controleur a instancier
            // On lui met une majuscule en premiere lettre et on lui rajoute 'Controller' (array_shift() renvoi le premier element du tableau en le retirant de celui-ci)
            // A ce point $controller contient le namespace le chemin ainsi que le nom du controleur tel que exemple : \Kasroman\Controllers\MonController
            $controller = '\\Kasroman\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';

            // On instancie le controleur
            $controller = new $controller();

            // Si $params[] contient encore un element alors on récupère cet element dans $action, sinon $action prend 'index'
            // C'est note deuxieme paramètre d'url
            $action = isset($params[0]) ? array_shift($params) : 'index';

            if(method_exists($controller, $action)){

                // Si il reste des elements dans l'url (des paramètres suplémentaires) on les donne en paramètre à la méthode du controleur
                // Au lieu d'écrire $controller->$action($params) on utilise la fonction call_user_func_array() qui permet de faire appel a une méthode d'un controleur en lui donnant un paramètre qui n'est pas forcement un tableau
                isset($params[0]) ? call_user_func_array([$controller, $action], $params) : $controller->$action();

            }else{
                http_response_code(404);
                echo 'La page recherchée n\'existe pas';
            }
        }else{
            // On a pas de paramètres, on instancie le controleur par defaut
            $controller = new MainController;

            // On appelle la methode index()
            $controller->index();
        }
    }
}