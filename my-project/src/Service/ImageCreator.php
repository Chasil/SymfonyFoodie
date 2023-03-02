<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageCreator
{
    public function __construct(
        private string $publicDirectory,
        private HttpClientInterface $client)
    {
    }

    public function create(string $imageURL): string
    {
        $picture = $this->client->request(
            'GET',
            $imageURL
        );

        $originalFileName = pathinfo($imageURL, PATHINFO_FILENAME);
        $newFileName = $originalFileName.'_'.uniqid().'.'.pathinfo($imageURL, PATHINFO_EXTENSION);
        $filesystem = new Filesystem();
        $filesystem->appendToFile(
            $this->publicDirectory.'images/hosting/'.$newFileName,
            $picture->getContent());

        return $newFileName;
    }

    public function upload(UploadedFile $picture): string
    {
        $originalFileName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFileName.'_'.uniqid().'.'.$picture->guessExtension();
        $picture->move('images/hosting', $newFileName);

        return $newFileName;
    }
}
