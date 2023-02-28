<?php

namespace App\Command;

use App\Exception\InvalidApiUrl;
use App\Exception\RecipieNotExist;
use App\Service\RecipieCreatorLauncher;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(name: 'app:get-api-recipies')]
class GetApiRecipies extends Command
{
    private const API_LINK = 'https://www.themealdb.com/api/json/v1/1/search.php?f=';

    public function __construct(
        private RecipieCreatorLauncher $launcher,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {

        $letter = $this->getLetter($input, $output);

        try {
            $this->launcher->launch(
                self::API_LINK . $letter,
                function () use ($output) {
                    $output->writeln('Recipie added.');
                },
                function () use ($output) {
                    $output->writeln('Recipie already exist.');
                },
            );
        } catch (
            RedirectionExceptionInterface|
            DecodingExceptionInterface|
            ClientExceptionInterface|
            InvalidApiUrl|
            TransportExceptionInterface|
            ServerExceptionInterface|
            RecipieNotExist $exception
        ) {
            $this->logger->log('ERROR', $exception->getMessage(), ['Cron error' => $exception->getMessage()]);
        }

        return Command::SUCCESS;
    }
}