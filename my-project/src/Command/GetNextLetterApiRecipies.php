<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:get-next-letter-api-recipies')]
class GetNextLetterApiRecipies extends GetApiRecipies
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    protected function getLetter(
        InputInterface $input,
        OutputInterface $output,
    ): string
    {
        // TODO pobranie z bazy (lub cache?)
        return 'a';
    }
}