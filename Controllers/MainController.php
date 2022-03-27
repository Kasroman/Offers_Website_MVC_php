<?php

namespace Kasroman\Controllers;

class MainController extends Controller{
    public function index(){
        $this->render('main/index');
    }
}