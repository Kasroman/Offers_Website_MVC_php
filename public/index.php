<?php

use Kasroman\Autoloader;
use Kasroman\Core\Main;

// On definit une constante contenant le dossier racine du projet
define('ROOT', dirname(__DIR__));

// On importe l'autoloader
require_once ROOT . '/Autoloader.php';
Autoloader::register();

// On instancie Main
$app = new Main();

// On démarre l'application
$app->start();
