<?php

namespace Kasroman\Controllers;

use Kasroman\Models\AnnoncesModel;

class AdminController extends Controller{

    public function index(){

        // On vérifie si l'utilisateur est admin
        if($this->isAdmin()){
            $this->render('admin/index');
        }
    }

    private function isAdmin(){

        // On vérifie si connecté etr si "ROLE_ADMIN" et sans la colonne 'roles' de l'utilisateur
        if(isset($_SESSION['user']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])){
            return true;
        }else{

            // La page existe :p
            $_SESSION['error'] = 'La page à laquelle vous tentez d\'acceder n\'existe pas';
            header('Location: /site_annonces/public');
            exit;
        }
    }

    public function annonces(){
        if($this->isAdmin()){
            $annoncesModel = new AnnoncesModel;

            $annonces = $annoncesModel->getAll();

            $this->render('admin/annonces', ['annonces' => $annonces]);
        }
    }

    public function setActiveAnnonce($id){
        if($this->isAdmin()){
            $annoncesModel = new AnnoncesModel;

            // On récupère l'annonce par son id
            $annonceArray = $annoncesModel->get($id);
            // var_dump($annonce);die();

            // Si elle est active on la set inactive
            if($annonceArray){
                $annonce = $annoncesModel->hydrate($annonceArray);

                $annonce->setIs_active($annonce->getIs_active() ? 0 : 1);

                $annonce->update();

                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }else{
            // La page existe :p
            $_SESSION['error'] = 'La page à laquelle vous tentez d\'acceder n\'existe pas';
            header('Location: /site_annonces/public');
            exit;   
        }
    }
}