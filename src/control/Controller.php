<?php

require_once("view/View.php");
require_once("model/Animal.php");
require_once("model/AnimalBuilder.php");
require_once("view/ViewJson.php");

class Controller
{
    /**
     * the view
     */
    private $view;

    /**
     * the array of animals
     */
    private $animalsTab;


    /**
     * Constructor of the controller
     *
     * @param View $view the view
     * @param array $animalsTab the array of animals
     *
     */
    public function __construct(View $view, AnimalStorageSession $animalsTab)
    {
        $this->view = $view;
        $this->animalsTab = $animalsTab;
    }

    /**
     * Show the information of an animal
     *
     * @param string $id the name of the animal
     */
    public function showInformation($id)
    {
        $animal = $this->animalsTab->read($id);

        if ($animal !== null) {
            $this->view->prepareAnimalPage($animal);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }


    /**
     * Show the home page
     */
    public function showHomePage()
    {
        $this->view->prepareHomePage();
    }

    /**
     * Show the list of animals
     */
    public function showListPage()
    {
        $this->view->prepareListPage($this->animalsTab->readAll());
    }

    /**
     * Method that will show the update page
     *
     * @param string $id the name of the animal
     */
    public function showUpdatePage($id){
        $animal = $this->animalsTab->read($id);

        if ($animal !== null) {
            // Récupère les données de l'animal pour les transmettre à la vue
            $animalData = [
                AnimalBuilder::NAME_REF => $animal->getName(),
                AnimalBuilder::SPECIES_REF => $animal->getSpecies(),
                AnimalBuilder::AGE_REF => $animal->getAge(),
            ];

            $this->view->prepareAnimalCreationPage(new AnimalBuilder($animalData) , true);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    /**
     * Method that will show the delete page
     *
     * @param string $id the name of the animal
     */
    public function showDeletePage($id){
        $animal = $this->animalsTab->read($id);

        if ($animal !== null) {
            $this->view->prepareDeletePage($id);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    /**
     * Method that will show the json page
     * 
     * @param string $id the name of the animal
     */
    public function showJsonPage($id)
    {
        $animal = $this->animalsTab->read($id);

        if ($animal !== null) {
            $vieuwJson = new ViewJson();
            $vieuwJson->render([
                "name" => $animal->getName(),
                "species" => $animal->getSpecies(),
                "age" => $animal->getAge(),
            ]);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }


    /**
     * method that will save a new animal
     *
     * @param array $data the data of the animal
     */
    public function saveNewAnimal(array $data)
    {
        $animalBuilder = new AnimalBuilder($data);

        if ($animalBuilder->isValid()) {
            $animal = $animalBuilder->createAnimal();
            $this->animalsTab->create($animal);

            // Récupère la clé de l'animal dans le tableau
            $animalId = $this->getAnimalKey($animal);

            // Utilise la nouvelle méthode pour afficher la redirection
            $this->view->displayAnimalCreationSuccess($animalId);
        } else {
            // Utilise la nouvelle méthode pour afficher la page avec l'AnimalBuilder vide
            $this->view->prepareEmptyAnimalBuilderPage($animalBuilder->getError(), $data);
        }
    }

    /**
     * Save the modifications of an animal
     *
     * @param string $id the id of the animal to edit
     * @param array $data the data to update
     */
    public function saveAnimalUpdate($id, array $data){
        $animal = $this->animalsTab->read($id);

        if ($animal !== null) {
            $animalBuilder = new AnimalBuilder($data);

            if ($animalBuilder->isValid()) {

                // Met à jour les données de l'animal existant
                $updatedAnimal = $animalBuilder->createAnimal();
                $this->animalsTab->update($id, $updatedAnimal);

                // Utilise la nouvelle méthode pour afficher la redirection
                $this->view->displayAnimalUpdateSuccess($id);
            } else {
                // Ajoutez des messages de débogage ici
                echo "Données du formulaire invalides:<br>";
                
                // Utilise la nouvelle méthode pour afficher la page avec l'AnimalBuilder vide
                $this->view->prepareAnimalCreationPage($animalBuilder, true);
            }
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    /**
     * method that will delete an animal
     *
     * @param string $id the id of the animal to delete
     */
    public function deleteAnimal($id){
        $animal = $this->animalsTab->read($id);

        if ($animal !== null) {
            $this->animalsTab->delete($id);
            $this->view->displayAnimalDeleteSuccess($id);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }




    /**
     * method that will return the key of an animal
     *
     * @param Animal $animal the animal
     */
    private function getAnimalKey(Animal $animal)
    {

        foreach ($this->animalsTab->readAll() as $key => $value) {
            if ($value === $animal) {
                return $key;
            }
        }

        return null;
    }
}
