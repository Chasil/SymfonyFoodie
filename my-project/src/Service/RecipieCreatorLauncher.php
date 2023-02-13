<?php

namespace App\Service;

use App\Entity\Recipie;
use App\Serializer\RecipieCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

class RecipieCreatorLauncher
{
    public function __construct(
        private SerializerInterface $serializer,
        private ManagerRegistry $doctrine,
        private RecipieCreator $recipieCreator
    ) {
    }
    public function launch(
        string $apiURL,
        callable $callbackOnCreated,
        callable $callbackOnExisted,
    ): void {
        $jsonData = file_get_contents($apiURL);

        $recipieCollection = $this->serializer->deserialize($jsonData, RecipieCollection::class, 'json');

        foreach ($recipieCollection->getMeals() as $serializedRecipie) {
            if(!$this->doctrine->getRepository(Recipie::class)->findBy(['recipieId' => $serializedRecipie->getRecipieId()])) {
                $this->recipieCreator->prepareIngredients($serializedRecipie->getRecipie(), $serializedRecipie->getIngredients());
                $this->recipieCreator->prepareCategories($serializedRecipie->getRecipie(), $serializedRecipie->getCategory());
                $this->recipieCreator->prepareTags($serializedRecipie->getRecipie(), $serializedRecipie->getTag());
                $this->recipieCreator->create($serializedRecipie->getRecipie());
                $callbackOnCreated();
            } else {
                $callbackOnExisted();
            }
        }
    }
}