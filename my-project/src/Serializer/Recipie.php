<?php

namespace App\Serializer;

use App\Entity\Category;
use App\Entity\Tag;

class Recipie extends \App\Entity\Recipie
{
    private const INGREDIENT_NAME_KEY = 'strIngredient';
    private const INGREDIENT_MEASURE_KEY = 'strMeasure';
    private array $deserializedIngredients = [];
    private array $deserializedCategories = [];

    public function setStrMeal(string $name)
    {
        $this->setName($name);
    }

    public function setStrDescriptions()
    {
        $this->setDescription('');
    }

    public function setStrInstructions(string $preparation)
    {
        $this->setPreparation($preparation);
    }

    public function setStrMealThumb(string $photo)
    {
        $this->setPhoto($photo);
    }

    public function setStrCategory(string $category)
    {
        $categoryEntity = new Category();
        $categoryEntity->setName($category);
        $this->addCategory($categoryEntity);
    }

    public function setStrTags(string $tag)
    {
        $tagEntity = new Tag();
        $tagEntity->setName($tag);
        $this->addTag($tagEntity);
    }

    public function getDeserializedIngredients()
    {
        return $this->deserializedIngredients;
    }

    public function __set(string $name, $value)
    {
        if (str_starts_with($name, self::INGREDIENT_NAME_KEY)) {
            $ingredientKey = str_replace(self::INGREDIENT_NAME_KEY, '', $name);
            $this->deserializedIngredients[$ingredientKey]['name'] = $value;
        } elseif (str_starts_with($name, self::INGREDIENT_MEASURE_KEY)) {
            $ingredientKey = str_replace(self::INGREDIENT_MEASURE_KEY, '', $name);
            $this->deserializedIngredients[$ingredientKey]['measure'] = $value;
        }
    }

}