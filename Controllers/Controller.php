<?php

namespace Kasroman\Controllers;

class Controller{
    public function render(string $file, array $data = [], string $template = 'base'){

        // On extrait le contenu de $data
        extract($data);

        // On démarre le buffer de sortie
        // A partir de ce point, toute sortie est conservée en mémoire
        ob_start();

        // On créé le chemin vers la vue
        require_once ROOT . '/Views/' . $file . '.php';

        // On stoque le contenu du buffer dans la variable
        // Dans la variable $content j'ai tout le html du fichier chargé et exécuté par require_once
        $content = ob_get_clean();   

        // On charge et on exécute alors le tout dans notre fichier base dans Views
        require_once ROOT . '/Views/' . $template . '.php';
    }
}