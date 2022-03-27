<?php

namespace Kasroman\Controllers;

use Kasroman\Core\Form;
use Kasroman\Models\AnnoncesModel;

class AnnoncesController extends Controller{

    /**
     * Methode qui affiche une page listant toutes les annonces de la bdd
     *
     * @return void
     */
    public function index(){

        // On instancie le modèle correspondant à la table 'annonces'
        $annoncesModel = new AnnoncesModel;

        // On va chercher toutes les annonces actives en bdd par le modèle
        $annonces = $annoncesModel->getBy(['is_active' => 1]);

        // On donne en paramètre a render debut de chemin et une autre variable $annonces qui contient notre variable $annonce sous forme de tableau
        $this->render('annonces/index', ['annonces' => $annonces]);
    }

    /**
     * Methode qui affiche les annonces appartenant a un user connecté
     *
     * @return void
     */
    public function userAnnonces(){

        // On vérifie que l'utilisateur est connecté
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

            // On instancie le modèle
            $annoncesModel = new AnnoncesModel;

            // On va cherche l'annonce
            $annonces = $annoncesModel->getBy(['users_id' => $_SESSION['user']['id']]);

            // On envoie à la vue
            $this->render('annonces/userAnnonces', ['annonces' => $annonces]);
        }else {
            $_SESSION['error'] = 'Vous devez être connecté(e) pour accéder à cette page';
            header('Location: /site_annonces/public/users/login');
            exit;
        }
    }

    /**
     * Affiche une annonce
     *
     * @param integer $id
     * @return void
     */
    public function read(int $id){
        // On instancie le modèle
        $annoncesModel = new AnnoncesModel;

        // On va cherche l'annonce
        $annonce = $annoncesModel->get($id);

        // On envoie à la vue
        $this->render('annonces/read', ['annonce' => $annonce]);
    }


    /**
     * Ajouter une annonce
     *
     * @return void
     */
    public function add(){

        // On vérifie si le formulaire est complet
        if(Form::validate($_POST, ['title', 'description'])){

            // On nettoie les champs
            $title = strip_tags($_POST['title']);
            $description = strip_tags($_POST['description']);

            // On instancie notre modèle
            $annonce = new AnnoncesModel;

            // On "hydrate"
            $annonce->setTitle($title)
                ->setDescription($description)
                ->setUsers_id($_SESSION['user']['id']);

            // On enregistre en bdd
            $annonce->create();

            // On redirige
            $_SESSION['success'] = 'Votre annonce a été enregistrée avec succès !';
            header('Location: /site_annonces/public/annonces/userAnnonces');
            exit();
        }else{
            // $_SESSION['error'] = 'Le formulaire n\'est pas valide veuillez remplir les champs';
            // header('Location: /site_annonces/public/annonces/add');
            // exit;
        }

        // On créé le formulaire de création d'annonce
        // On vérifie si l'utilisateur est connecté
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

            $form = new Form;

            $form->startForm()
                ->addLabelFor('title', 'Titre de l\'annonce :')
                ->addInput('text', 'title', ['id' => 'title', 'class' => 'form-control'])
                ->addLabelFor('description', 'Description de l\'annonce')
                ->addTextarea('description', '', ['id' => 'description', 'class' => 'form-control'])
                ->addButton('Ajouter', ['class' => 'btn btn-primary'])
                ->endForm();

            $this->render('annonces/add', ['form' => $form->create()]);

        }else{
            $_SESSION['error'] = 'Vous devez être connecté(e) pour accéder à cette page';
            header('Location: /site_annonces/public/users/login');
            exit;
        }
    }

    public function update(int $id){

        // On vérifie que l'user est connecté
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
            
            // On vérifie que l'annonce existe dans la base
            $annoncesModel = new AnnoncesModel;

            // On cherche l'annonce apr son id
            $annonce = $annoncesModel->get($id);

            // Si l'annonce n'existe pas, on retourne à la liste de ses annonces
            if(!$annonce){
                http_response_code(404);
                $_SESSION['error'] = 'L\'annonce recherchée n\'existe pas';
                header('Location: /site_annonces/public/annonces/userAnnonces');
                exit();
            }

            // On vérifie si l'utilisateur est propriétaire de l'annonce ou si il est admin
            if($annonce->users_id !== $_SESSION['user']['id']){
                if(!in_array('ROLE_ADMIN', $_SESSION['user']['roles'])){
                    
                // Elle existe mais on ne lui révèle pas
                $_SESSION['error'] = 'L\'annonce recherchée n\'existe pas';
                header('Location: /site_annonces/public/annonces/userAnnonces');
                exit();
                }
            }

            // On traite le formulaire
            if(Form::validate($_POST, ['title', 'description'])){

                // On nettoie les inputs utilisateurs
                $title = strip_tags($_POST['title']);
                $description = strip_tags($_POST['description']);

                // On stoque l'annonce
                $annonceUpdate = new AnnoncesModel;

                // On hydrate
                $annonceUpdate->setId($annonce->id)
                    ->setTitle($title)
                    ->setDescription($description);
                
                // On met à jour
                $annonceUpdate->update();

                // On redirige
                $_SESSION['success'] = 'Votre annonce a été modifiée avec succès';
                header("Location: /site_annonces/public/annonces/read/{$id}");
                exit();
            }

            $form = new Form;

            $form->startForm()
                ->addLabelFor('title', 'Titre de l\'annonce :')
                ->addInput('text', 'title', [
                    'id' => 'title', 
                    'class' => 'form-control',
                    'value' => $annonce->title
                ])
                ->addLabelFor('description', 'Description de l\'annonce')
                ->addTextarea('description', $annonce->description, [
                    'id' => 'description',
                    'class' => 'form-control',
                ])
                ->addButton('Modifier', ['class' => 'btn btn-primary'])
                ->endForm();
            
            // On envoie a la vue
            $this->render('annonces/update', ['form' => $form->create()]);

        }else{
            $_SESSION['error'] = 'Vous devez être connecté(e) pour accéder à cette page';
            header('Location: /site_annonces/public/users/login');
            exit;
        }
    }

    public function remove(int $id){

        // On vérifie que l'user est connecté
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
            
            // On vérifie que l'annonce existe dans la base
            $annoncesModel = new AnnoncesModel;

            // On cherche l'annonce apr son id
            $annonce = $annoncesModel->get($id);

            // Si l'annonce n'existe pas, on retourne à la liste de ses annonces
            if(!$annonce){
                http_response_code(404);
                $_SESSION['error'] = 'L\'annonce recherchée n\'existe pas';
                header('Location: /site_annonces/public/annonces/userAnnonces');
                exit();
            }

            // On vérifie si l'utilisateur est propriétaire de l'annonce ou si il est admin
            if($annonce->users_id !== $_SESSION['user']['id']){
                if(!in_array('ROLE_ADMIN', $_SESSION['user']['roles'])){

                // Elle existe mais on ne lui révèle pas
                $_SESSION['error'] = 'L\'annonce recherchée n\'existe pas';
                header('Location: /site_annonces/public/annonces/userAnnonces');
                exit();
                }
            }
            
            // On supprime de la bbd
            $annoncesModel->delete($id);

            // On redirige
            // Si il est admin on le redirige vers la page ou il est actuellement
            if(in_array('ROLE_ADMIN', $_SESSION['user']['roles'])){
                $_SESSION['success'] = 'L\'annonce a été supprimée avec succès !';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();             
            }else{

                // Sinon on redirige l'utilisateur sur ses annonces
                $_SESSION['success'] = 'Votre annonce a été supprimée avec succès !';
                header('Location: /site_annonces/public/annonces/userAnnonces');
                exit();
            }

        }else{
            $_SESSION['error'] = 'Vous devez être connecté(e) pour accéder à cette page';
            header('Location: /site_annonces/public/users/login');
            exit;
        }
    }
}