<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipie;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $doctrineManager = $doctrine->getManager();
        $recipies = $doctrineManager->getRepository(Recipie::class)->findBy(['isVisible' => 1]);
        $categories = $doctrineManager->getRepository(Category::class)->findGroupedCategories();
        $tags = $doctrineManager->getRepository(Tag::class)->findAll();

        return $this->render('index/index.html.twig', [
            'recipies' => $recipies,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }
}
