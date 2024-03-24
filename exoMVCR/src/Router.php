<?php

include_once "View/View.php";
include_once "View/ViewJSON.php";
include_once "Control/Controller.php";


class Router{
    public $storage;
    public $animalBuilder;
    public function __construct($storage,$animalBuilder){
        $this->storage = $storage;
        $this->animalBuilder=$animalBuilder;

    }

    public function main(){
        //session_start();
        if(!key_exists('feedback',$_SESSION)){
            $_SESSION['feedback'] = "Première connexion sur le site ";
        }
        $view = new View("Ceci est la page de base","Qui est tres basique",$this,$_SESSION['feedback']);
        $viewJSON = new ViewJSON("pas de JSON");
        $showJSON = false;
        $_SESSION['feedback'] = null;
        $controleur = new Controller($view,$viewJSON,$this->storage);
        if(key_exists('PATH_INFO',$_SERVER)){
            $chemin = explode("/",$_SERVER['PATH_INFO']);
            if(count($chemin)==3){
                if($chemin[1]==='modifier'){
                    $ancienAnimal = $this->storage->read($chemin[2]);
                    $view->prepareAnimalModificationPage($this->animalBuilder,$ancienAnimal);   
                }
                else if($chemin[1]==='supprimer'){
                    $view->prepareAnimalSuppresion($this->animalBuilder,$chemin[2]);
                }
                else if($chemin[1]==='json'){
                    $controleur->showListWithDetails($chemin[2]);
                    $showJSON = true;
                }
                else $controleur->showHomePage();
            }
            else if (count($chemin)==2){
                if($chemin[1]==='nouveau'){
                    $view->prepareAnimalCreationPage($this->animalBuilder);
                
                }
                else if($chemin[1]==='sauverNouveau'){
                    $controleur->saveNewAnimal($_POST);
                }  
                
                else if($chemin[1]==='sauverModification'){
                    $controleur->saveUpdateAnimal($_POST);
                }

                else if($chemin[1]==='confirmerSuppresion'){
                    $controleur->destroyAnimal();
                }
                

                else if($chemin[1]==="liste"){
                    $controleur->showList();
                }

                else{
                    $id = $chemin[1];
                    $_SESSION['id'] = $id;
                    $controleur->showInformation($id);
                }
            }
            else $controleur->showHomePage();
        }
        else $controleur->showHomePage();
        
        //$view->prepareTestPage();
        //$view->prepareAnimalPage("Médor","Chien");
        
        if($showJSON==false) echo($view->render());
        else echo($viewJSON->render());

    }

    public function getAccueilURL(){
        return $_SERVER["SCRIPT_NAME"];
    }

    public function getAnimalURL($id){
        return $_SERVER["SCRIPT_NAME"]."/".$id;
    }
    
    public function getAnimalCreationURL(){
        return $_SERVER["SCRIPT_NAME"]."/nouveau";
    }
    
    public function getAnimalSaveURL(){
        return $_SERVER["SCRIPT_NAME"]."/sauverNouveau";
    }

    public function getAnimalModifURL(){
        return $_SERVER["SCRIPT_NAME"]."/sauverModification";
    }

    public function getAnimalModificationURL($id){
        return $_SERVER["SCRIPT_NAME"]."/modifier/".$id;
    }

    public function getAnimalDestroy($id){
        return $_SERVER["SCRIPT_NAME"]."/supprimer/".$id;
    }

    public function getConfirmationSuppresion(){
        return $_SERVER["SCRIPT_NAME"]."/confirmerSuppresion";

    }
    public function getDetailsURL($id){
        return $_SERVER["SCRIPT_NAME"]."/json/".$id;
    }

    public function POSTredirect($url,$feedback){

        $_SESSION['feedback'] = $feedback;
        header('Location:'.$url,false,303);
        die;
    }

}
?>
