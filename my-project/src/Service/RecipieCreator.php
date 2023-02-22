<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipie;
use App\Entity\Tag;
use App\Entity\User;
use App\Exception\ImageCreatorFailure;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipieCreator extends AbstractController {

    public function __construct
    (private ManagerRegistry $doctrine,
     private ImageCreator $imageCreator,
     private LoggerInterface $logger
    )
    {
    }

    /**
     * @param Recipie $recipie
     * @param array<TKey, array{name: string, measure: string}> $ingredients
     * @return void
     */
    public function prepareIngredients(Recipie $recipie, array $ingredients): void
    {
        $doctrineManager = $this->doctrine->getManager();

        foreach($ingredients as $ingredient) {
            if (!empty($ingredient['name'] || !empty($ingredient['measure']))) {
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
    public function prepareTags(
        Recipie $recipie,
        ?array $tags
    ): void
    {
        if ($tags) {
            foreach ($tags as $tag) {
                if (!empty($tag)) {
                    /** @var TagRepository $tagRepository */
                    $tagRepository = $this->doctrine->getManager()->getRepository(Tag::class);
                    $tag = $tagRepository->getTagByName($tag);
                    $recipie->addTag($tag);
                }
            }
        }
    }

    /**
     * @param Recipie $recipie
     * @param string $categoryName
     * @return void
     */
    public function prepareCategories(Recipie $recipie, string $categoryName): void
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->doctrine->getManager()->getRepository(Category::class);
        $category = $categoryRepository->getCategoryByName($categoryName);
        $recipie->addCategory($category);
    }

    /**
     * @param Recipie $recipie
     * @return bool
     */
    public function create(Recipie $recipie): void
    {
        $doctrineManager = $this->doctrine->getManager();

        if (!$this->getUser()) {
            $apiUser = $doctrineManager->getRepository(User::class)->findOneBy(['username' => 'api']);
            $recipie->setUser($apiUser);
        } else {
            $recipie->setUser($this->getUser());
        }

        try {
            $newFileName = $this->imageCreator->create($recipie->getPhoto());
        } catch (ImageCreatorFailure $exception) {
            $this->addFlash('error', $exception->getMessage());
            $this->logger->log('ERROR', $exception->getMessage(), ['source' => $recipie->getPhoto()]);
        }

        $recipie->setPhoto($newFileName);

        $doctrineManager->persist($recipie);
        $doctrineManager->flush();
    }
}
