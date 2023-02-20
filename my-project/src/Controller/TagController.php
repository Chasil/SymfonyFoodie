<?php

namespace App\Controller;

use App\Entity\Recipie;
use App\Entity\Tag;
use App\Repository\RecipieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag/{name}/{page}', name: 'tag', requirements: ['page' => '\d+'])]
    public function index(
        ManagerRegistry $doctrine,
        Tag $tag,
        PaginatorInterface $paginator,
        Request $request,
        $page = 1): Response
    {
        $recipieRepository = $doctrine->getRepository(Recipie::class);
        $recipiesQuery = $recipieRepository->queryByTagName($tag->getName());

        $pagination = $paginator->paginate(
            $recipiesQuery,
            $request->query->getInt('page', $page),
            5
        );

        return $this->render('tag/index.html.twig', [
            'recipiesPagination' => $pagination,
            'tag' => $tag
        ]);
    }
}
