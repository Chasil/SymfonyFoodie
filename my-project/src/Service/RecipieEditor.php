<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipie;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RecipieEditor extends AbstractController {

    public function __construct(private ManagerRegistry $doctrine, private ImageCreator $imageCreator)
    {
    }

    /**
     * @param Recipie $recipie
     * @param array<TKey, array{name: string, measure: string}> $ingredients
     * @return void
     */
    public function prepareIngredients(Recipie $recipie, array $ingredients): void
    {
        foreach($ingredients as $ingredient => $measure) {
            $ingredientRepository = $this->doctrine->getManager()->getRepository(Ingredient::class);
            $ingredient = $ingredientRepository->getIngredientByName($ingredient, $measure);
            $recipie->addIngredient($ingredient);
        }
    }

    /**
     * @param Recipie $recipie
     * @param array $tags
     * @return void
     */
    public function prepareTags(
        Recipie $recipie,
        array $tags
    ): void
    {
        foreach($tags as $tag) {
            /** @var TagRepository $tagRepository */
            $tagRepository = $this->doctrine->getManager()->getRepository(Tag::class);
            $tag = $tagRepository->getTagByName($tag);
            $recipie->addTag($tag);
        }
    }

    /**
     * @param Recipie $recipie
     * @param array $categories
     * @return void
     */
    public function prepareCategories(Recipie $recipie, array $categories): void
    {
        foreach($categories as $category) {
            $categoryRepository = $this->doctrine->getManager()->getRepository(Category::class);
            $categoryEntity = $categoryRepository->getCategoryByName($category);
            $recipie->addCategory($categoryEntity);
        }
    }

    /**
     * @param Recipie $recipie
     * @param Collection $validatedElements
     * @param array $validArray
     * @param string $collectionName
     * @return void
     */
    public function removeRepetitions(
        Recipie $recipie,
        Collection $validatedElements,
        array $validArray,
        string $collectionName
    ): void
    {
        $functionName = 'remove' . $collectionName;
        foreach ($validatedElements as $validatedElement) {
            $name = $validatedElement->getName();
            if (!in_array($name, $validArray)) {
                $recipie->$functionName($validatedElement);
            }
        }
    }

    /**
     * @param Recipie $recipie
     * @param UploadedFile|null $photo
     * @return bool
     */
    public function edit(Recipie $recipie, ?UploadedFile $photo): bool
    {
        $doctrineManager = $this->doctrine->getManager();
        $recipie->setUser($this->getUser());

        $newFileName = $this->imageCreator->upload($photo);

        $recipie->setPhoto($newFileName);

        $doctrineManager->persist($recipie);
        $doctrineManager->flush();

        return true;
    }
}
