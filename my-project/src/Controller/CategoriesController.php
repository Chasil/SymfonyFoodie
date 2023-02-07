<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getManager()->getRepository(Category::class)->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
