<?php

namespace App\Serializer;

class RecipieCollection
{
    /**
     * @var Recipie[]
     */
    public $meals = [];

    /**
     * @return Recipie[]
     */
    public function getMeals(): array
    {
        return $this->meals;
    }
}
