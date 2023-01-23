<?php

namespace App\Controller;

use App\Entity\Recipie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $doctrineManager = $doctrine->getManager();

        $categories = $doctrineManager->getRepository(Recipie::class)->findAllCategories();

        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
            'categories' => $categories
        ]);
    }
}
