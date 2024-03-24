<?php
include_once "Model/Animal.php";
include_once "Model/AnimalStorage.php";
include_once "Model/AnimalBuilder.php";
class Controller{
    public $view;
    public $viewJSON;
    public $animalsTab;
    public $animalStorage;
    public function __construct($view,$viewJSON,$storage){
        $this->view=$view;
        $this->viewJSON=$viewJSON;
        $this->animalStorage = $storage;
    }

    public function showInformation($id) {
        if(key_exists($id,$this->animalStorage->readAll())){
            $this->view->prepareAnimalPage($this->animalStorage->read($id),$id);
            
        }
        else{
            $this->view->prepareUnknownAnimalPage();

        }
    }

    public function showHomePage(){
        $this->view->prepareHomePage();
    }

    public function showList(){
        $this->view->prepareListPage($this->animalStorage->readAll());

    }

    public function saveNewAnimal(array $data){
        $animalBuilder = new AnimalBuilder($data);
        if($animalBuilder->isValid()){
            $animal= $animalBuilder->createAnimal();
            $id = $this->animalStorage->create($animal);
            $this->view->displayAnimalCreationSuccess($id);
        }
        else{
            $error = $animalBuilder->getError();
            $this->view->prepareAnimalCreationPage($animalBuilder);

        }
            //$this->view->prepareDebugPage($data);
            
    }

    public function saveUpdateAnimal(array $data){
        $animalBuilder = new AnimalBuilder($data);
        if($animalBuilder->isValid()){
            $animal =$animalBuilder->createAnimal();
            $this->animalStorage->update($_SESSION['id'],$animal);
            $this->view->displayAnimalUpdateSuccess($_SESSION['id']);
            unset($_SESSION['id']);//pour eviter les key exists
        }
        else{
            $error = $animalBuilder->getError();
            $ancienAnimal = $this->animalStorage->read($_SESSION['id']);
            $this->view->prepareAnimalModificationPage($animalBuilder,$ancienAnimal);
            

        }

    }

    public function destroyAnimal(){
        $res =$this->animalStorage->delete($_SESSION['id']);
        if($res){
            $this->view->displayAnimalDestroySuccess(); 
            unset($_SESSION['id']);
        }
        else{
            $this->view->displayAnimalDestroyUnSuccess($_SESSION['id']); 
            unset($_SESSION['id']);

        }
        
        
    }

    public function showListWithDetails($id){
        $this->viewJSON->prepareJSON($this->animalStorage->readAll(),$id);
    }

}
?>