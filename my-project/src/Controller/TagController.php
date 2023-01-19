<?php

namespace App\Controller;

use App\Entity\Recipie;
use App\Entity\Tags;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag/{tagName}', name: 'tagName')]
    public function index(string $tagName, ManagerRegistry $doctrine): Response
    {

        $doctrineManager = $doctrine->getManager();
        /** @var Tags $tag */
        $tag = $doctrineManager->getRepository(Tags::class)->findOneBy(['name' => $tagName]);
        $recipies = $tag->getRecipies()->getValues();

        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }
}
