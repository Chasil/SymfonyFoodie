<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{name}', name: 'category')]
    public function index(Category $category): Response
    {
        $recipies = $category->getRecipies()->getValues();

        return $this->render('category/index.html.twig', [
            'recipies' => $recipies,
            'category_name' => $category->getName()
        ]);
    }
}
