<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipie;
use App\Repository\RecipieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{name}/{page}', name: 'category', requirements: ['page' => '\d+'])]
    public function index(ManagerRegistry $doctrine, Category $category, $page = 1): Response
    {
        $perPage = 5;
        $recipieRepository = $doctrine->getRepository(Recipie::class);
        /** @var RecipieRepository $recipieRepository */
        $recipies = $recipieRepository->countByCategoryName($category->getName());

        $pageCount = ceil($recipies / $perPage);
        if ($page-1 > $pageCount) {
            throw new \Exception();
        }

        $pagedRecipies = $recipieRepository->getByCategoryName($category->getName(), $perPage, $perPage * ($page-1));

        return $this->render('category/index.html.twig', [
            'recipies' => $pagedRecipies,
            'category' => $category,
            'page' => (int) $page,
            'totalPages' => (int) $pageCount
        ]);
    }
}
