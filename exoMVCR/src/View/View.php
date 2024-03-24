<?php
include_once "Router.php";
include_once "Model/AnimalBuilder.php";
class View{

    public $title;
    public $content;
    public $routeur;
    public $feedback;
    public $menu;
    public $maybeScript;
    public function __construct($title,$content,$routeur,$feedback) {

        $this->title=$title;
        $this->content=$content;
        $this->routeur = $routeur;
        $this->feedback = $feedback;
        $this->maybeScript="";
        $this->menu =array(
            $_SERVER["SCRIPT_NAME"] => "Page d'Accueil",
            $_SERVER["SCRIPT_NAME"].'/liste'=>"Liste d'Animaux",
            $_SERVER["SCRIPT_NAME"].'/nouveau'=>"Création d'Animaux",
        ); 
        
    }

    public function render():string{
        $renvoie = '<!DOCTYPE html>
                    <html lang="fr">
                        <head>
                            <title>'.$this->title.'</title>
                            '.$this->maybeScript.'
                        </head>
                        <body>
                            <p>'.$this->feedback.'</p>
                            <div class="menu">
                            	<ul>';
                            	foreach($this->menu as $url=>$texte){
                            		$renvoie .='<li><a href="'.$url.'">'.$texte.'</a></li>';
                            	}
                            	$renvoie.='</ul>
                            </div>
                            <h1>'.$this->title.'</h1>
                            <div>'
                              .$this->content.
                            '</div>
                        </body>
                    </html>';
        return $renvoie;

    }

    public function prepareTestPage():void{
        $this->title = "Bonjour";
        $this->content = "Ceci est une page test"; 
    }

    public function prepareAnimalPage($animal,$id):void{
        $this->title="Page sur ".$animal->getName();
        $this->content=$animal->getName()." est un animal de l'espece ".$animal->getSpecies().", et il a ".$animal->getAge()." ans";
        $this->content.= "<form action ='".$this->routeur->getAnimalModificationURL($id)."' method='POST'>";
        $this->content .= "<button type='submit'> Modifier </button>";
        $this->content .= "</form>";

        $this->content.= "<form action ='".$this->routeur->getAnimalDestroy($id)."' method='POST'>";
        $this->content .= "<button type='submit'> Supprimer </button>";
        $this->content .= "</form>";
      
        
    }

    public function prepareUnknownAnimalPage(){
        $this->title = "Erreur";
        $this->content ="Il y a eu un probleme je connais pas ton animal";
    }

    public function prepareHomePage(){
        $this->title = "Page d'accueil";
        $this->content = "Bienvenue sur la page d'accueil du site ! ";
    }

    public function prepareListPage($tab){
        $this->title = "Liste des noms";
        $this->maybeScript='<script src="../src/View/Details.js"></script>';
        $this->content = "<ul id='listePage'>";
        foreach($tab as $key=>$value){
            $this->content .="<li id='".$key."'>";
            $this->content .= '<a href="'.$this->routeur->getAnimalURL($key).'">'.$value->getName().'</a> ';
            $this->content .="</li>";
        }
        
        $this->content .= "</ul>";
        $this->content .='<script>"use strict";init();</script>';
    }
    
    public function prepareDebugPage($variable) {
	$this->title = 'Debug';
	$this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
    }

    public function prepareAnimalCreationPage($animalBuilder){
        
        $nom = "";
        $espece = "";
        $age = "";
        $error = $animalBuilder->getError();
        $data = $animalBuilder->getData();
        
        if($data!=null){
            if(key_exists($animalBuilder::NAME_REF,$data)){
                $nom=$data[$animalBuilder::NAME_REF];
            }
            if(key_exists($animalBuilder::SPECIES_REF,$data)){
                $espece=$data[$animalBuilder::SPECIES_REF];
            }
            if(key_exists($animalBuilder::AGE_REF,$data)){
                $age=$data[$animalBuilder::AGE_REF];
            }
        }
        
        $this->title = "Creation Animal";
        $this->content =" <form action ='".$this->routeur->getAnimalSaveURL()."' method='POST'>";
        $this->content .= "<label>Nom  : <input type='text' name='".$animalBuilder::NAME_REF."' value='".$nom."'/></label>";
        if(key_exists($animalBuilder::NAME_REF,$error)){
            $this->content .="<font color='red'>". $error[$animalBuilder::NAME_REF]."</font>"; 
        }
        $this->content .= "<label>Espece : <input type='text' name='".$animalBuilder::SPECIES_REF."' value='".$espece."'/></label>";
        if(key_exists($animalBuilder::SPECIES_REF,$error)){
            $this->content .="<font color='red'>". $error[$animalBuilder::SPECIES_REF]."</font>"; 
        }
        $this->content .= "<label>Age : <input type='number' name='".$animalBuilder::AGE_REF."' value='".$age."'/></label>";
        if(key_exists($animalBuilder::AGE_REF,$error)){
            $this->content .="<font color='red'>". $error[$animalBuilder::AGE_REF]."</font>"; 
        }
        $this->content .= "<button type='submit'> Envoyer </button>";
        $this->content .= "</form>";
    }

    public function prepareAnimalModificationPage($animalBuilder,$ancienAnimal){
        $nom=$ancienAnimal->getName();
        $espece=$ancienAnimal->getSpecies();
        $age=$ancienAnimal->getAge();
        $error = $animalBuilder->getError();
        $data = $animalBuilder->getData();

        if($data!=null){
            if(key_exists($animalBuilder::NAME_REF,$data)){
                $nom=$data[$animalBuilder::NAME_REF];
            }
            if(key_exists($animalBuilder::SPECIES_REF,$data)){
                $espece=$data[$animalBuilder::SPECIES_REF];
            }
            if(key_exists($animalBuilder::AGE_REF,$data)){
                $age=$data[$animalBuilder::AGE_REF];
            }
        }

        $this->title = "Modification Animal";
        $this->content =" <form action ='".$this->routeur->getAnimalModifURL()."' method='POST'>";
        $this->content .= "<label>Nom  : <input type='text' name='".$animalBuilder::NAME_REF."' value='".$nom."'/></label>";
        if(key_exists($animalBuilder::NAME_REF,$error)){
            $this->content .="<font color='red'>". $error[$animalBuilder::NAME_REF]."</font>"; 
        }
        $this->content .= "<label>Espece : <input type='text' name='".$animalBuilder::SPECIES_REF."' value='".$espece."'/></label>";
        if(key_exists($animalBuilder::SPECIES_REF,$error)){
            $this->content .="<font color='red'>". $error[$animalBuilder::SPECIES_REF]."</font>"; 
        }
        $this->content .= "<label>Age : <input type='number' name='".$animalBuilder::AGE_REF."' value='".$age."'/></label>";
        if(key_exists($animalBuilder::AGE_REF,$error)){
            $this->content .="<font color='red'>". $error[$animalBuilder::AGE_REF]."</font>"; 
        }
        $this->content .= "<button type='submit'> Envoyer </button>";
        $this->content .= "</form>";
        
        
    }

    public function prepareAnimalSuppresion($animalBuilder,$id){
        $this->title ="Confirmation de la supression?";
        $this->content = "<form action ='".$this->routeur->getAnimalURL($id)."' method='POST'>";
        $this->content .= "<button type='submit'> Annuler </button>";
        $this->content .= "</form>";
        
        $this->content .="<form action ='".$this->routeur->getConfirmationSuppresion()."' method='POST'>";
        $this->content .= "<button type='submit'> Supprimer </button>";
        $this->content .= "</form>";
    }

    public function displayAnimalCreationSuccess($id){
        $this->routeur->POSTredirect($this->routeur->getAnimalURL($id),"L'animal a été créé avec succès. ");
    }

    public function displayAnimalUpdateSuccess($id){
        $this->routeur->POSTredirect($this->routeur->getAnimalURL($id),"L'animal a été modifié avec succès. ");

    }

    public function displayAnimalDestroySuccess(){
        $this->routeur->POSTredirect($this->routeur->getAccueilURL(),"L'animal a été supprimé avec succès. ");
    }

    public function displayAnimalDestroyUnSuccess($id){
        $this->routeur->POSTredirect($this->routeur->getAnimalURL($id),"L'animal n'a pas été supprimé. ");
    }


}
?>
