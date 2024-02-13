<?php

interface AnimalStorage
{
    /**
     * method that will read the name of the animal
     *
     * @param string $name the name of the animal
     */
    public function read($name);

    /**
     * method that will read all the animals
     */
    public function readAll();

    /**
     * method that will create an animal
     */
    public function create(Animal $animal);

    /**
     * method that will update an animal
     *
     * @param string $id the name of the animal
     * @param Animal $animal the animal to update
     */
    public function update($id, Animal $animal);

    /**
     * method that will delete an animal
     *
     * @param string $id the name of the animal
     */
    public function delete($id);

}

