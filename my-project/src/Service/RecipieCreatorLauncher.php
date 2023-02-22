<?php

namespace App\Service;

use App\Api\ApiClient;
use App\Entity\Recipie;
use App\Serializer\RecipieCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

class RecipieCreatorLauncher
{
    public function __construct(
        private SerializerInterface $serializer,
        private ManagerRegistry $doctrine,
        private RecipieCreator $recipieCreator,
        private ApiClient $apiClient
    ) {
    }

    /**
     * @param string $apiURL
     * @param callable $callbackOnCreated
     * @param callable $callbackOnExisted
     * @return void
     * @throws \Exception
     */
    public function launch(
        string $apiURL,
        callable $callbackOnCreated,
        callable $callbackOnExisted,
    ): void {
        $apiData = $this->apiClient->fetchTheMealDbInformation($apiURL);
        $recipieCollection = $this->serializer->deserialize($apiData, RecipieCollection::class, 'json');

        foreach ($recipieCollection->getMeals() as $serializedRecipie) {
            if (!$this->doctrine->getRepository(Recipie::class)->findBy(['recipieId' => $serializedRecipie->getRecipieId()])) {
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