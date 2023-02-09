<?php

namespace App\Command;

use App\Entity\Recipie;
use App\Serializer\RecipieCollection;
use App\Service\RecipieCreator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(name: 'app:get-api-recipies')]
class GetApiRecipies extends Command
{
    private const API_LINK = 'https://www.themealdb.com/api/json/v1/1/search.php?f=';

    public function __construct(
        private SerializerInterface $serializer,
        private ManagerRegistry $doctrine,
        private RecipieCreator $recipieCreator
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {

        $io = new SymfonyStyle($input, $output);
        $letter = $io->ask('Type single letter to get recipies starting with this letters.', 1, function($letter) {
            if(!preg_match('/^[a-z]$/', $letter)) {
                throw new \RuntimeException('Value must be a single letter!');
            }
            return $letter;
        });

        $jsonData = file_get_contents(self::API_LINK . $letter);

        $recipieCollection = $this->serializer->deserialize($jsonData, RecipieCollection::class, 'json');

        foreach($recipieCollection->getMeals() as $serializedRecipie) {
            if(!$this->doctrine->getRepository(Recipie::class)->findBy(['recipieId' => $serializedRecipie->getRecipieId()])) {
                $this->recipieCreator->prepareIngredients($serializedRecipie->getRecipie(), $serializedRecipie->getIngredients());
                $this->recipieCreator->prepareCategories($serializedRecipie->getRecipie(), $serializedRecipie->getCategory());
                $this->recipieCreator->prepareTags($serializedRecipie->getRecipie(), $serializedRecipie->getTag());
                $this->recipieCreator->create($serializedRecipie->getRecipie());
                $output->writeln('Recipie added.');
            } else {
                $output->writeln('Recipie exists.');
            }
        }
        return Command::SUCCESS;
    }
}