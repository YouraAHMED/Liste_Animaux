<?php

require_once("model/Animal.php");

class View
{

    protected $router;
    protected $feedback;
    /**
     * the title of the page
     */
    private $title;
    /**
     * the content of the page
     */
    private $content;
    /**
     * The menu of the page
     */
    private $menu = array(
        "Accueil" => "site.php",
        "Liste des animaux" => "site.php?liste",
        "Créer un animal" => "site.php?action=nouveau",
    );

    /**
     * Constructor of the view
     */
    public function __construct(Router $router, $feedback)
    {
        $this->router = $router;
        $this->feedback = $feedback;

    }

    /**
     * method render that will show the page html
     */
    public function render()
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title><?php echo $this->title ?></title>
            <link rel="stylesheet" href="src/style/style.css">
            <script src="src/js/script.js"></script>
        </head>

        <body>
        <?php if ($this->feedback !== null) { ?>
            <p> <?= $this->feedback ?></p>
        <?php } ?>
        <nav>
            <ul>
                <?php
                foreach ($this->menu as $key => $value) {
                    echo "<li class='menu'><a href='" . $value . "'>" . $key . "</a></li>";
                }
                ?>
            </ul>
            <h1><?php echo $this->title ?></h1>
        </nav>

        <div class="content-container">
            <?php echo $this->content ?>
        </div>

        </body>
        </html>
        <?php
    }

    /**
     * method that will prepare the test page
     */
    public function prepareTestPage()
    {
        $this->title = "le titre de la page";
        $this->content = "le contenu de la page";

    }

    /**
     * method that will prepare the animal page
     */
    public function prepareAnimalPage($Animal)
    {
        $this->title = "Page de " . $Animal->getName();
        $this->content = "<p>" . $Animal->getName() . " est un " . $Animal->getSpecies() . " qui a " . $Animal->getAge() . " ans</p>";

        // Ajoutez un lien "Modifier"
        $updateUrl = $this->router->getAnimalUpdatePageURL($_GET['id']);
        $this->content .= "<a href='$updateUrl'>Modifier</a>";

        // Ajoutez un lien "Supprimer"
        $deleteUrl = $this->router->getAnimalDeleteURL($_GET['id']);
        $this->content .= "<a href='$deleteUrl'>Supprimer</a>";


        $listePage = $this->router->getListURL();
        $this->content .= "<a href='$listePage'>Retour à la liste</a>";
    }

    /**
     * method that will prepare a error page
     */
    public function prepareUnknownAnimalPage()
    {
        $this->title = "Erreur 404";
        $this->content = "L'animal demandé n'existe pas";
    }

    /**
     * method that will prepare the home page
     */
    public function prepareHomePage()
    {
        $this->title = "Bienvenue sur mon site";
        $this->content = "c'est l'accueil";
    }

    /**
     * method that will prepare the list page
     * @param array $animalsTab the array of animals
     */
    public function prepareListPage($animalsTab)
    {
        $this->title = "Liste des animaux";
        $this->content = "<ul>";
        foreach ($animalsTab as $key => $animal) {
            $this->content .= "<li>
            <a href='site.php?id=" . $key . "'>" . $animal->getName() . "</a>
            <button class='animal-details-button' data-animal-id='$key'>Détails</button>
            <div id='animal-details-$key' style='display:none;'></div><br>
        </li>";
        }
        $this->content .= "</ul>";
    }

    /**
     * Method that will prepare the debug page
     *
     * @param $variable the variable to debug
     */
    public function prepareDebugPage($variable)
    {
        $this->title = 'Debug';
        $this->content = '<pre>' . htmlspecialchars(var_export($variable, true)) . '</pre>'; 
    }

    /**
     * Method that will prepare the animal creation page with an empty animal builder
     *
     * @param string $errorMessage the error message
     * @param array $data the data
     */
    public function prepareEmptyAnimalBuilderPage($errorMessage = null, $data = [])
    {
        $emptyBuilder = new AnimalBuilder($data);
        $this->prepareAnimalCreationPage($emptyBuilder);

        // Ajoutez le message d'erreur s'il y en a un
        if ($errorMessage) {
            $this->content .= "<p style='color: red;'>$errorMessage</p>";
        }
    }

    /**
     * Method that will prepare the animal creation page
     *
     * @param AnimalBuilder $animalBuilder the animal builder
     * @param bool $isEditing flag to indicate if it's for updating or creating
     */
    public function prepareAnimalCreationPage(AnimalBuilder $animalBuilder, bool $isEditing = false)
    {
        $nameValue = $animalBuilder->getData()[AnimalBuilder::NAME_REF] ?? '';
        $speciesValue = $animalBuilder->getData()[AnimalBuilder::SPECIES_REF] ?? '';
        $ageValue = $animalBuilder->getData()[AnimalBuilder::AGE_REF] ?? '';

        $this->title = $isEditing ? "Modification d'un animal" : "Création d'un animal";
        $this->content = "<div class='form-container'><form action='" . ($isEditing ? $this->router->getAnimalUpdateURL($_GET['id']) : $this->router->getAnimalSaveURL()) . "' method='post'>
        <label for='" . AnimalBuilder::NAME_REF . "'>Nom</label>
        <input type='text' name='" . AnimalBuilder::NAME_REF . "' id='" . AnimalBuilder::NAME_REF . "' value='" . $nameValue . "'>
        <label for='" . AnimalBuilder::SPECIES_REF . "'>Espèce</label>
        <input type='text' name='" . AnimalBuilder::SPECIES_REF . "' id='" . AnimalBuilder::SPECIES_REF . "' value='" . $speciesValue . "'>
        <label for='" . AnimalBuilder::AGE_REF . "'>Age</label>
        <input type='text' name='" . AnimalBuilder::AGE_REF . "' id='" . AnimalBuilder::AGE_REF . "' value='" . $ageValue . "'>";

        if ($isEditing) {
            $this->content .= "<input type='hidden' name='id' value='" . $_GET['id'] . "'>";
        }

        $this->content .= "<input type='submit' value='" . ($isEditing ? "Modifier" : "Créer") . "'>";

        if ($isEditing) {
            $this->content .= "<a href='" . $this->router->getAnimalURL($_GET['id']) . "'>Annuler</a>";
        }

        $this->content .= "</form></div>";

        if ($animalBuilder->getError()) {
            $this->content .= "<p class='error-message'>" . $animalBuilder->getError() . "</p>";
        }

    }

    /**
     * Method that will prepare the confirmation page for animal deletion
     *
     * @param string $id the ID of the animal to be deleted
     */
    public function prepareDeletePage($id)
    {
        $this->title = "Confirmation de suppression";
        $this->content = "<p>Êtes-vous sûr de vouloir supprimer cet animal ?</p>";

        // Form for confirmation with a POST request
        $this->content .= "<form action='" . $this->router->getAnimalDeleteConfirmationURL($id) . "' method='post'>
        <input type='hidden' name='action' value='confirmerSuppression'>
        <button type='submit'>Confirmer la suppression</button>
        <a href='" . $this->router->getAnimalURL($id) . "'>Annuler</a>
    </form>";
    }



    /**
     * Method that will display the animal creation success
     *
     * @param string $id the name of the animal
     */
    public function displayAnimalCreationSuccess($id)
    {
        $url = "site.php?id=" . $id;
        $this->router->POSTredirect($url, "<div>Animal créé avec succès</div>");
    }

    /**
     * Method that will display the animal update success
     *
     * @param string $id the name of the animal
     */
    public function displayAnimalUpdateSuccess($id)
    {
        $url = "site.php?id=" . $id;
        $this->router->POSTredirect($url, "<div>Animal modifié avec succès</div>");
    }

    /**
     * Method that will display the animal deletion success
     *
     * @param string $id the name of the animal
     */
    public function displayAnimalDeleteSuccess($id){

        $url = "site.php";
        $this->router->POSTredirect($url, "<div>$id a été supprimé avec succès</div>");
    }

}
