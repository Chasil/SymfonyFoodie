<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipie;
use App\Entity\Tag;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipieCreator extends AbstractController {

    public function __construct(private ManagerRegistry $doctrine, private ImageCreator $imageCreator)
    {
    }

    /**
     * @param Recipie $recipie
     * @param array<TKey, array{name: string, measure: string}> $ingredients
     * @return void
     */
    public function prepareIngredients(Recipie $recipie, array $ingredients)
    {
        $doctrineManager = $this->doctrine->getManager();

        foreach($ingredients as $ingredient) {
            if(!empty($ingredient['name'] || !empty($ingredient['measure']))) {
                /** @var IngredientRepository $ingredientRepository */
                $ingredientRepository = $doctrineManager->getRepository(Ingredient::class);
                $ingredient = $ingredientRepository->getIngredientByName($ingredient['name'], $ingredient['measure']);
                $recipie->addIngredient($ingredient);
            }
        }
    }

    /**
     * @param Recipie $recipie
     * @param array $tags
     * @return void
     */
    public function prepareTags(Recipie $recipie, string $tag)
    {
        $doctrineManager = $this->doctrine->getManager();
        $tags = $this->splitItemsToArray($tag);

        foreach($tags as $tag) {
            /** @var TagRepository $tagRepository */
            $tagRepository = $doctrineManager->getRepository(Tag::class);
            $tag = $tagRepository->getTagByName($tag);
            $recipie->addTag($tag);
        }
    }

    public function prepareCategories(Recipie $recipie, string $category)
    {
        $doctrineManager = $this->doctrine->getManager();

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $doctrineManager->getRepository(Category::class);
        /** @var Category $category */
        $category = $categoryRepository->getCategoryByName($category);
        $recipie->addCategory($category);
    }

    public function create(Recipie $recipie): bool
    {
        $doctrineManager = $this->doctrine->getManager();
        $recipie->setUser($this->getUser());

        $newFileName = $this->imageCreator->create($recipie->getPhoto());
        $recipie->setPhoto($newFileName);

        $doctrineManager->persist($recipie);
        $doctrineManager->flush();

        return true;
    }

    // TODO Przenieść do osobnego serwisu, bo obecnie jest 2x
    /**
     * @param string $string
     * @return array
     */
    public function splitItemsToArray(string $items): array
    {
        return array_map(
            'trim',
            explode(",", $items)
        );
    }
}