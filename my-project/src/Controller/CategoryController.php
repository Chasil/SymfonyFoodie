<?php

namespace App\Controller;

use App\Entity\Recipie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{category}', name: 'category')]
    public function index(string $category, ManagerRegistry $doctrine): Response
    {
        $doctrineManager = $doctrine->getManager();

        $recipies = $doctrineManager->getRepository(Recipie::class)->findBy(['category'=>$category]);

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'recipies' => $recipies,
            'category_name' => $category
        ]);
    }
}
