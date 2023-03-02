<?php

namespace App\Api;

use App\Exception\InvalidApiUrlException;
use App\Exception\RecipieNotExistException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private const DOMAIN_NAME = 'themealdb.com';

    public function __construct(private HttpClientInterface $client)
    {
    }

    /**
     * @throws InvalidApiUrlException
     * @throws RecipieNotExistException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function fetchTheMealDbInformation(string $apiURL): string
    {
        if (!$this->isURLValid($apiURL)) {
            throw new InvalidApiUrlException();
        }

        $response = $this->client->request(
            'GET',
            $apiURL
        );

        if (!$this->doesRecipieExist($response->toArray())) {
            throw new RecipieNotExistException();
        }

        return $response->getContent();
    }

    private function doesRecipieExist(array $recipies): bool
    {
        return !empty($recipies['meals']);
    }

    private function isURLValid(string $apiURL): bool
    {
        return str_contains($apiURL, self::DOMAIN_NAME);
    }
}
