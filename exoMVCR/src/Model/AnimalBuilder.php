<?php
class AnimalBuilder{
    public $data;
    public $error;
    public const NAME_REF ="NOM";
    public const SPECIES_REF ="espece";
    public const AGE_REF ="age";
    public function __construct($data){
        $this->data=$data;
        $this->error = [];
    }
    
    public function createAnimal(){//special chars afin d'eviter l'injection de script dans l'animal
        
        return  new Animal(htmlspecialchars($this->data[self::NAME_REF]),htmlspecialchars($this->data[self::SPECIES_REF]),htmlspecialchars($this->data[self::AGE_REF]));
    } 

    public function isValid(){
        
        if(trim($this->data[self::NAME_REF]," ") ===""){
            $this->error[self::NAME_REF] ="Un nom est requis ^^'";
        }
        if(trim($this->data[self::SPECIES_REF]," ") ===""){
            $this->error[self::SPECIES_REF] ="Une espece est requise ^^'";

        }
        if($this->data[self::AGE_REF]<0 || $this->data[self::AGE_REF]===""){
            $this->error[self::AGE_REF] ="Un nombre compris dans l'ensemble N est requis svp ^^'";

        }
            
        if($this->error!==[]){
            return false;
        }
        else{
            return true;

        }
    }

    public function getData(){
        return $this->data;
    }

    public function getError(){
        return $this->error;
    }
}
?>