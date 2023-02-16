<?php

namespace App\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private $client;

    public function __construct(HttpClientInterface $client, private ApiManager $apiManager)
    {
        $this->client = $client;
    }

    public function fetchTheMealDbInformation(string $apiURL): mixed
    {
        $response = $this->client->request(
            'GET',
            $apiURL
        );

        if (!$this->apiManager->doesRecipieExist($response->toArray())) {
            return false;
        }

        return $response->getContent();
    }
}