<?php
class Animal{
    public $name;
    public $species;
    public $age;

    public function __construct($name,$species,$age){
        $this->name = $name;
        $this->species = $species;
        $this->age = $age;

    }

    public function getName():string{
        return $this->name;

    }

    public function getSpecies():string{
        return $this->species;

    }
    
    public function getAge():string{
        return $this->age;

    }
}
?>