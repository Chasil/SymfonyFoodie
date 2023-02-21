<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipie;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/{page}', name: 'index', requirements: ['page' => '\d+'])]
    public function index(
        ManagerRegistry $doctrine,
        PaginatorInterface $paginator,
        Request $request,
        $page = 1): Response
    {
        $recipieRepository = $doctrine->getRepository(Recipie::class);
        $recipiesQuery = $recipieRepository->queryAll();

        $pagination = $paginator->paginate(
            $recipiesQuery,
            $request->query->getInt('page', $page),
            $this->getParameter('app.per_page')
        );

        return $this->render('index/index.html.twig', [
            'recipiesPagination' => $pagination,
            'categories' => $doctrine->getRepository(Category::class)->findGroupedCategories(),
            'tags' => $doctrine->getRepository(Tag::class)->findAll()
        ]);
    }
}
