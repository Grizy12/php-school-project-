<?php
class AnimalStorageStub implements AnimalStorage{
    public $animalsTab;

    public function __construct(){
        $this->animalsTab = array(
            'medor' => new Animal("Médor","chien",7),
            'felix' => new Animal("Felix","chat",5),
            'denver' => new Animal("Denver","dinosaure",251_000_000),
            'galen' => new Animal("Galen","Belveth",20),
        );
    }

    public function read($id){
        if(key_exists($id,$this->animalsTab)){
            return $this->animalsTab[$id];
        }
        else{
            return Null;
        }

        

    }

    public function readAll(){
        return $this->animalsTab;
        
    }
    
    public function create(Animal $a){
    	throw new Exception("Methode obsolete");
    }
    public function delete($id){
    	throw new Exception("Methode obsolete");
    }
     
    public function update($id,Animal $a){
    	throw new Exception("Methode obsolete");
    }
    
 

}
?>
