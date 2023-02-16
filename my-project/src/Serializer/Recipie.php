<?php

namespace App\Serializer;

use App\HandleArrayInput;

class Recipie
{
    use HandleArrayInput;

    private const INGREDIENT_NAME_KEY = 'strIngredient';
    private const INGREDIENT_MEASURE_KEY = 'strMeasure';
    private array $ingredients = [];
    private \App\Entity\Recipie $recipie;
    private string $category;
    private ?string $tag;
    private int $recipieId;

    public function __construct()
    {
        $this->recipie = new \App\Entity\Recipie();
    }

    public function setIdMeal(string $recipieId): void
    {
        $this->recipieId = $recipieId;
        $this->recipie->setRecipieId($recipieId);
    }

    public function getRecipieId(): int
    {
        return $this->recipieId;
    }

    public function setStrMeal(string $name): void
    {
        $this->recipie->setName($name);
    }

    public function setStrInstructions(string $preparation): void
    {
        $this->recipie->setPreparation($preparation);
    }

    public function setStrMealThumb(string $photo): void
    {
        $this->recipie->setPhoto($photo);
    }

    public function setStrCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getRecipie(): \App\Entity\Recipie
    {
        return $this->recipie;
    }

    public function setStrTags(?string $tag): void
    {
        $this->tag = $tag;
    }

    public function getTag(): ?array
    {
        if ($this->tag) {
            return $this->transformStringToArray($this->tag);
        } else {
            return $this->tag;
        }
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function __set(string $name, $value): void
    {
        if (str_starts_with($name, self::INGREDIENT_NAME_KEY)) {
            $ingredientKey = str_replace(self::INGREDIENT_NAME_KEY, '', $name);
            $this->ingredients[$ingredientKey]['name'] = $value;
        } elseif (str_starts_with($name, self::INGREDIENT_MEASURE_KEY)) {
            $ingredientKey = str_replace(self::INGREDIENT_MEASURE_KEY, '', $name);
            $this->ingredients[$ingredientKey]['measure'] = $value;
        }
    }
}