<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:get-api-recipies')]
class GetInputLetterApiRecipie extends GetApiRecipies
{
    protected function getLetter(
        InputInterface $input,
        OutputInterface $output,
    ): string {
        $io = new SymfonyStyle($input, $output);

        return (string) $io->ask(
            'Type single letter to get recipies starting with this letters.',
            1,
            function ($letter) {
                if (!preg_match('/^[a-z]$/', $letter)) {
                    throw new \RuntimeException('Value must be a single letter!');
                }

                return $letter;
            }
        );
    }
}
