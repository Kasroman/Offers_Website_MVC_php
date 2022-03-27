<?php

namespace Kasroman\Controllers;

use Kasroman\Core\Form;
use Kasroman\Models\UsersModel;

class UsersController extends Controller{

    /**
     * Connexion
     *
     * @return string
     */
    public function login(){

        // On vérifie si le formulaire est complet
        if(Form::validate($_POST, ['email', 'password'])){
            // On va chercher dans la bdd l'utilisateur avec l'email
            $usersModel = new UsersModel;
            $userArray = $usersModel->getOneByEmail(strip_tags($_POST['email']));

            // Si il n'existe pas
            if(!$userArray){

                // On envoie un message de session
                $_SESSION['error'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect';
                header('Location: /site_annonces/public/users/login');
                exit;
            }

            $user = $usersModel->hydrate($userArray);

            // On vérifie si le mdp est correct
            if(password_verify($_POST['password'], $user->getPassword())){

                // On ouvre la session
                $user->setSession();
                header('Location: /site_annonces/public/');
                exit;
            }else{

                // Mauvais mdp
                $_SESSION['error'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect';
                header('Location: /site_annonces/public/users/login');
                exit;
            }
        }

        // On créé le formulaire
        $form = new Form;
        $form->startForm()
            ->addLabelFor('email', 'E-mail :')
            ->addInput('email', 'email', ['class' => 'form-control', 'id' => 'email'])
            ->addLabelFor('password','Mot de passe :')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
            ->addButton('Me connecter', ['class' => 'btn btn-primary'])
            ->endForm();

        $this->render('users/login', ['loginForm' => $form->create()]);
    }

    /**
     * Déconnecter utilisateur
     *
     * @return void
     */
    public function logout(){
        unset($_SESSION['user']);

        // On redirige vers la page où est actuellement l'utilisateur
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Inscription
     *
     * @return void
     */
    public function register(){
        
        // On vérifie si le formulaire retourné est valide
        if(Form::validate($_POST, ['email', 'password'])){

            // On "nettoie" l'addresse mail
            $email = strip_tags($_POST['email']);

            // On hash le mdp
            $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

            // On "hydrate" l'utilisateur
            $user = new UsersModel;

            $user->setEmail($email)
                ->setPassword($password);

            // On stoque l'utilisateur
            $user->create();
            $_SESSION['success'] = 'Vous vous êtes inscrit avec succès ! Connectez-vous.';
            header('Location: /site_annonces/public/users/login');
            exit();
        }

        // On créé le formulaire 
        $form = new Form;
        $form->startForm()
            ->addLabelFor('email', 'E-mail :')
            ->addInput('email', 'email', ['class' => 'form-control', 'id' => 'email'])
            ->addLabelFor('password','Mot de passe :')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
            ->addButton('M\'inscrire', ['class' => 'btn btn-primary'])
            ->endForm();
        
        $this->render('users/register', ['registerForm' => $form->create()]);

    }
}