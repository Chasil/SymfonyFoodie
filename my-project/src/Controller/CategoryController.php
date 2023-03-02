<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipie;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{name}/{page}', name: 'category', requirements: ['page' => '\d+'])]
    public function index(
        ManagerRegistry $doctrine,
        Category $category,
        PaginatorInterface $paginator,
        Request $request,
        $page = 1): Response
    {
        $recipieRepository = $doctrine->getRepository(Recipie::class);
        $recipiesQuery = $recipieRepository->queryByCategoryName($category->getName());

        $pagination = $paginator->paginate(
            $recipiesQuery,
            $request->query->getInt('page', $page),
            $this->getParameter('app.per_page')
        );

        return $this->render('category/index.html.twig', [
            'recipiesPagination' => $pagination,
            'category' => $category,
        ]);
    }
}
