<?php
include_once "Router.php";
include_once "Model/AnimalBuilder.php";
class ViewJSON{

    public $content;
    
    public function __construct($content) {

        $this->content=$content;
        
    }

    public function render():string{
        $renvoie = $this->content;
        return $renvoie;

    }

    public function prepareJSON($tab,$id){
        header("Content-Type: application/json");
        $this->content = "";
        foreach($tab as $key=>$value){
            if($key===$id){
                $details=json_encode(array(
                    "id" => $id, // Je rajoute l'id qui n'est pas demandé pour me simplifier la vie pour les fonctions de Details.js
                    "nom" => $value->getName(),
                    "espece" => $value->getSpecies(),
                    "age" => $value->getAge(),
                ),JSON_UNESCAPED_UNICODE);
                $this->content .= $details;
            }
        }
    }

}
?>
