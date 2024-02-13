<?php

class AnimalBuilder
{

    const NAME_REF = 'name';
    const SPECIES_REF = 'species';
    const AGE_REF = 'age';


    private $data;
    private $error;

    public function __construct($data, $error = null)
    {
        $this->data = $data;
        $this->error = $error;
    }

    /**
     * Renvoie les données de l'animal en cours de construction
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Renvoie l'éventuelle erreur associée à la construction de l'animal
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Crée une nouvelle instance de Animal en utilisant les données actuelles
     */
    public function createAnimal()
    {
        return new Animal($this->data['name'], $this->data['species'], $this->data['age']);
    }

    /**
     * Vérifie que les données actuelles sont correctes
     */
    public function isValid()
    {
        $name = $this->data[self::NAME_REF];
        $species = $this->data[self::SPECIES_REF];
        $age = $this->data[self::AGE_REF];

        if (empty($name) || empty($species) || empty($age) || !is_numeric($age) || $age < 0) {
            $this->error = "Tous les données sont invalides";
            return false;
        }

        return true;
    }
}
