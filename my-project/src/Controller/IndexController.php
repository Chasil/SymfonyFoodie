<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipie;
use App\Entity\Tag;
use App\Repository\RecipieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/{page}', name: 'index', requirements: ['page' => '\d+'])]
    public function index(ManagerRegistry $doctrine, $page = 1): Response
    {
        $perPage = 5;

        $doctrineManager = $doctrine->getManager();
        /** @var RecipieRepository $recipieRepository */
        $recipieRepository = $doctrineManager->getRepository(Recipie::class);
        $findCriteria = ['isVisible' => 1];
        $pageCount = ceil($recipieRepository->count($findCriteria) / $perPage);

        if ($page-1 > $pageCount) {
            throw new \Exception();
        }

        $recipies = $recipieRepository->findBy(
            $findCriteria,
            null,
            $perPage,
            $perPage * ($page-1)
        );

        return $this->render('index/index.html.twig', [
            'recipies' => $recipies,
            'categories' => $doctrineManager->getRepository(Category::class)->findGroupedCategories(),
            'tags' => $doctrineManager->getRepository(Tag::class)->findAll(),
            'page' => (int) $page,
            'totalPages' => $pageCount
        ]);
    }
}
