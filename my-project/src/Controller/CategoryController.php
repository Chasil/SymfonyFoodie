<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{categoryName}', name: 'category')]
    public function index(string $categoryName, ManagerRegistry $doctrine): Response
    {
        $doctrineManager = $doctrine->getManager();
        /** @var Category $category */
        $category = $doctrineManager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
        $recipies = $category->getRecipies()->getValues();

        return $this->render('category/index.html.twig', [
            'recipies' => $recipies,
            'category_name' => $categoryName
        ]);
    }
}
