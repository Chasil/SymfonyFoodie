<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipie;
use App\Repository\CategoryRepository;
use App\Repository\RecipieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Collection;
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
        $recipies = $recipieRepository->count(['categories' => new ArrayCollection([$category])]);

        $pageCount = $recipies->count(['isVisible' => 1]) / $perPage;

        if ($page - 1 > $pageCount) {
            throw new \Exception();
        }

        $pagedRecipies = $recipieRepository->findBy(
            ['category_id' => $category->getId(), 'isVisible' => 1],
            null,
            $perPage,
            $perPage * ($page-1)
        );

        return $this->render('category/index.html.twig', [
            'recipies' => $pagedRecipies,
            'category' => $category
        ]);
    }
}
