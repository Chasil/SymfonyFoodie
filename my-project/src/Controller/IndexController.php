<?php

namespace App\Controller;

use App\Entity\Recipie;
use App\Entity\Tags;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $doctrineManager = $doctrine->getManager();
        /** @var Recipie $recipies */
        $recipies = $doctrineManager->getRepository(Recipie::class)->findBy(['isVisible' => 1]);
        // co do kategorii to w sumie źle do tego podszedłem, trzeba by zrobić to na zasadzie tagów ale nie dam rady dziś zmienić
        $categories = $doctrineManager->getRepository(Recipie::class)->findGroupedCategories();
        $tags = $doctrineManager->getRepository(Tags::class)->findAll();

        return $this->render('index/index.html.twig', [
            'recipies' => $recipies,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }
}
