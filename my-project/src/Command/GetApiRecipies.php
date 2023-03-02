<?php

namespace App\Command;

use App\Exception\GetApiRecipiesException;
use App\Service\RecipieCreatorLauncher;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

abstract class GetApiRecipies extends Command
{
    private const API_LINK = 'https://www.themealdb.com/api/json/v1/1/search.php?f=';

    public function __construct(
        private RecipieCreatorLauncher $launcher,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    final protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        try {
            $letter = $this->getLetter($input, $output);

            $this->launcher->launch(
                self::API_LINK.$letter,
                function () use ($output) {
                    $output->writeln('Recipie added.');
                },
                function () use ($output) {
                    $output->writeln('Recipie already exist.');
                },
            );
        } catch (GetApiRecipiesException) {
            return Command::INVALID;
        } catch (\Exception $exception) {
            $this->logger->log('ERROR', $exception->getMessage(), ['Cron error' => $exception->getMessage()]);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @throws GetApiRecipiesException
     */
    abstract protected function getLetter(
        InputInterface $input,
        OutputInterface $output,
    ): string;
}
