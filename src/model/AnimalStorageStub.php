<?php

require_once("model/AnimalStorage.php");

class AnimalStorageStub implements AnimalStorage
{
    private $animals;

    public function __construct()
    {
        $this->animals = array(
            new Animal("Medor", "Chien", 10),
            new Animal("Felix", "Chat", 5),
            new Animal("Rex", "Chien", 3),
            new Animal("Denver", "Dinosaure", 100),
            new Animal("Flipper", "Dauphin", 15),
            new Animal("Titi", "Oiseau", 1),
            new Animal("Dumbo", "Elephant", 50),
            new Animal("Rantanplan", "Chien", 8),
            new Animal("Rudolph", "Renard", 4),
            new Animal("Bambi", "Cerf", 2),
            new Animal("Simba", "Lion", 12),
            new Animal("Nemo", "Poisson", 0.5),
            new Animal("Stuart", "Souris", 0.2),

        );
    }

    public function read($name)
    {
        foreach ($this->animals as $animal) {
            if (strtolower($animal->getName()) === strtolower($name)) {
                return $animal;
            }
        }
        return null;
    }

    public function readAll()
    {
        return $this->animals;
    }

    public function create(Animal $animal)
    {
        throw new Exception("Not implemented");
    }

    public function update($name, Animal $animal)
    {
        throw new Exception("Not implemented");
    }

    public function delete($name)
    {
        throw new Exception("Not implemented");
    }

}
