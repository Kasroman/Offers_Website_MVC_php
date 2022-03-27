<?php

namespace Kasroman\Core;

class Form{
    private $formCode = '';

    /**
     * Génère le formulaire html
     *
     * @return string
     */
    public function create(){
        return $this->formCode;
    }

    /**
     * Valide si tous les champs proposés du formulaire sont remplis
     *
     * @param array $form
     * @param array $fields
     * @return bool
     */
    public static function validate(array $form, array $fields){

        // On parcourt les champs
        foreach($fields as $field){

            // Si le champ est absent ou vide dans le formulaire
            if(!isset($form[$field]) || empty($form[$field])){
                return false;
            }
        }
        return true;
    }

    /**
     * Ajoute les attributs envoyés à la balise
     *
     * @param array $attributs exemple : ['class' => 'form-control', 'required' => true]
     * @return string
     */
    public function addAttributs(array $attributs):string{
        
        // On initialise une chaine de caractères
        $str = '';

        // On liste les attributs qui n'ont pas de valeurs
        $short = ['checked', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formnovalidate'];

        // On boucle sur le tableau $attributs
        foreach($attributs as $attribut => $value){
            if(in_array($attribut, $short) && $value == true){
                $str .= " $attribut";
            }else{

                // Si il a une valeur on ajoute attribut='valeur'
                // Ici on échape les guillemets car ça gène lors d'input dans le html
                $str .= " $attribut=\"$value\"";
            }
        }
        return $str;
    }

    /**
     * Balise d'ouverture du formulaire
     *
     * @param string $method (post/get)
     * @param string $action
     * @param array $attributs
     * @return Form
     */
    public function startForm(string $method = 'post', string $action = '#', array $attributs = []):self{

        // On créé la balise form
        $this->formCode .= "<form action='$action' method='$method'";

        // On ajoute les potentiels attributs
        $this->formCode .= $attributs ? $this->addAttributs($attributs) . '>' : '>';

        return $this;
    }

    /**
     * Balise de fermeture du form
     *
     * @return Form
     */
    public function endForm():self{
        $this->formCode .= '</form>';
        return $this;
    }

    /**
     * Ajout de label
     *
     * @param string $for
     * @param string $text
     * @param array $attributs
     * @return Form
     */
    public function addLabelFor(string $for, string $text, array $attributs = []):self{

        // On ouvre la balise
        $this->formCode .= "<label for='$for'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';

        // On ajoute le texte
        $this->formCode .= ">$text</label>";

        return $this;
    }

    /**
     * Ajout de l'input
     *
     * @param string $type
     * @param string $name
     * @param array $attributs
     * @return Form
     */
    public function addInput(string $type, string $name, array $attributs = []):self{

        $this->formCode .= "<input type='$type' name='$name'";
        $this->formCode .= $attributs ? $this->addAttributs($attributs) . '>' : '>';
        return $this; 
    }

    /**
     * Ajout de textarea
     *
     * @param string $name
     * @param string $value
     * @param array $attributs
     * @return Form
     */
    public function addTextarea(string $name, string $value = '', array $attributs = []):self{

        // On ouvre la balise
        $this->formCode .= "<textarea name='$name'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';

        // On ajoute le texte
        $this->formCode .= ">$value</textarea>";

        return $this;
    }

    /**
     * Ajout de select
     *
     * @param string $name
     * @param array $options
     * @param array $attributs
     * @return Form
     */
    public function addSelect(string $name, array $options, array $attributs = []):self{
        // On créé le select
        $this->formCode .= "<select name'$name'";

        $this->formCode .= $attributs ? $this->addAttributs($attributs) . '>' : '>';

        // On rajoute les options
        foreach($options as $value => $text){
            $this->formCode .= "<option value=\"$value\">$text</option>";
        }

        // On ferme le select
        $this->formCode .= '</select>';

        return $this;
    }

    /**
     * Ajout de button
     *
     * @param string $text
     * @param array $attributs
     * @return Form
     */
    public function addButton(string $text, array $attributs = []):self{
        $this->formCode .= '<button ';
        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';
        $this->formCode .= ">$text</button>";
        return $this;
    }
}