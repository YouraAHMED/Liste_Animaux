<?php

class Animal
{

    /**
     * the name of the animal
     */
    private $name;

    /**
     * the species of the animal
     */
    private $species;

    /**
     * the age of the animal
     */
    private $age;


    /**
     * the constructor of the class
     *
     * @param string $name the name of the animal
     * @param string $species the species of the animal
     * @param int $age the age of the animal
     */
    public function __construct($name, $species, $age)
    {
        $this->name = $name;
        $this->species = $species;
        $this->age = $age;
    }

    /**
     * method that will return the age of the animal
     */
    public function getName()
    {

        return $this->name;
    }


    /**
     * method that will return the age of the animal
     */
    public function getSpecies()
    {

        return $this->species;
    }

    /**
     * method that will return the age of the animal
     */
    public function getAge()
    {

        return $this->age;
    }


}

