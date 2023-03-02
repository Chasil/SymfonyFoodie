<?php

namespace App\Command;

use App\Exception\EndOfAlphabetException;
use App\Service\RecipieCreatorLauncher;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:get-next-letter-api-recipies')]
class GetNextLetterApiRecipies extends GetApiRecipies
{
    public function __construct(
        private RecipieCreatorLauncher $launcher,
        private LoggerInterface $logger,
        private CacheItemPoolInterface $cache
    ) {
        parent::__construct($this->launcher, $this->logger);
    }

    /**
     * @throws InvalidArgumentException
     * @throws EndOfAlphabetException
     */
    protected function getLetter(
        InputInterface $input,
        OutputInterface $output,
    ): string {
        $cachedItem = $this->cache->getItem('last_letter');
        $currentLetter = $cachedItem->get() ?: 'a';
        if ('aa' === $currentLetter) {
            throw new EndOfAlphabetException();
        }

        $cachedItem->set(++$currentLetter);
        $cachedItem->expiresAfter(60 * 60 * 3);
        $this->cache->save($cachedItem);

        return $currentLetter;
    }
}
