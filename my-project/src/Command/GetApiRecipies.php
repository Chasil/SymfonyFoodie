<?php

namespace App\Command;

use App\Service\RecipieCreatorLauncher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:get-api-recipies')]
class GetApiRecipies extends Command
{
    private const API_LINK = 'https://www.themealdb.com/api/json/v1/1/search.php?f=';

    public function __construct(
        private RecipieCreatorLauncher $launcher
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

        $apiURL = self::API_LINK . $letter;

        $createRecipie = $this->launcher->launch($apiURL);
        $output->writeln($createRecipie['created'] . ' recipies added.');
        $output->writeln($createRecipie['existed'] . ' recipies exist.');

        return Command::SUCCESS;
    }
}