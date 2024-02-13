<?php

require_once("view/View.php");
require_once("control/Controller.php");
require_once("model/AnimalStorage.php");
require_once("model/AnimalStorageSession.php");


class Router
{
    public function main()
    {
        // Début de la mise en mémoire tampon
        ob_start(); 
        session_start();
        $animalsTab = new AnimalStorageSession();
        $view = new View($this, $_SESSION['feedback'] ?? null);
        unset($_SESSION['feedback']);
        $controller = new Controller($view, $animalsTab);

        if (key_exists('id', $_GET)) {
            $controller->showInformation($_GET['id']);
        } elseif (key_exists('liste', $_GET)) {
            $controller->showListPage();
        } else {
            $controller->showHomePage();
        }
        if (key_exists('action', $_GET)) {
            if ($_GET['action'] == 'nouveau') {
                $animalBuilder = new AnimalBuilder([]);
                $view->prepareAnimalCreationPage($animalBuilder);
            } elseif ($_GET['action'] == 'sauverNouveau') {
                $controller->saveNewAnimal($_POST);
            } elseif ($_GET['action'] == 'modifier' && key_exists('id', $_GET)) {
                $controller->showUpdatePage($_GET['id']);
            } elseif ($_GET['action'] == 'sauverModification' && key_exists('id', $_GET)) {
                $controller->saveAnimalUpdate($_GET['id'], $_POST);
            }
            elseif ($_GET['action'] == 'supprimer' && key_exists('id', $_GET)) {
                $controller->showDeletePage($_GET['id']);
            }
            elseif ($_GET['action'] == 'confirmerSuppression' && key_exists('id', $_GET)) {
                $controller->deleteAnimal($_GET['id']);
            }
        }
        $view->render();
        // Fin de la mise en mémoire tampon, récupère le contenu tamponné sans l'envoyer
        $output = ob_get_clean();


        // Envoyer le JSON uniquement s'il s'agit d'une action JSON
        if (key_exists('action', $_GET) && $_GET['action'] == 'json' && key_exists('id', $_GET)) {
            $controller->showJsonPage($_GET['id']);
        } else {
            // Sinon, envoyer le contenu HTML tamponné
            echo $output;
        }
    }

    /**
     * Method that will return the url of the creation of an animal
     */
    public function getAnimalCreationURL()
    {
        return "site.php?action=nouveau";
    }

    /**
     * Method that will return the url of the update of an animal
     */
    public function getAnimalSaveURL()
    {
        return "site.php?action=sauverNouveau";
    }

    /**
     * Method that will return the url of the update of an animal
     */
    public function getAnimalUpdateURL($id){
        return "site.php?action=sauverModification&id=$id";
    }

    /**
     * Method that will return the url of the update of an animal
     */
    public function getAnimalUpdatePageURL($id){
        return "site.php?action=modifier&id=$id";
    }

    /**
     * Method that will return the url of the deletion of an animal
     *
     * @param string $id the name of the animal
     */
    public function getAnimalDeleteURL($id){
        return "site.php?action=supprimer&id=$id";
    }

    /**
     * Method that will return the url of the deletion confirmation of an animal
     *
     * @param string $id the name of the animal
     */
    public function getAnimalDeleteConfirmationURL($id){
        return "site.php?action=confirmerSuppression&id=$id";
    }

    /**
     * Method that will return the url of the list of animals
     */
    public function getListURL(){
        return "site.php?liste";
    }

    /**
     * Method that will return the url of animal information
     */
    public function getAnimalURL($id){
        return "site.php?id=$id";
    }



    /**
     * Method that will return the url of the home page
     *
     * @param string $url the url of the page
     * @param string $feedback the feedback to display
     *
     */
    public function POSTredirect($url, $feedback)
    {
        $_SESSION['feedback'] = $feedback;
        header("Location: $url");
        die;
    }
}
