<?php

namespace App\Controller;

use App\Entity\Recipie;
use App\Entity\Tag;
use App\Repository\RecipieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag/{name}/{page}', name: 'tag', requirements: ['page' => '\d+'])]
    public function index(ManagerRegistry $doctrine, Tag $tag, $page = 1): Response
    {
        $perPage = 5;
        $recipieRepository = $doctrine->getRepository(Recipie::class);
        /** @var RecipieRepository $recipieRepository */
        $recipies = $recipieRepository->countByTagName($tag->getName());

        $pageCount = ceil($recipies / $perPage);
        if ($page-1 > $pageCount) {
            throw new \Exception();
        }

        $pagedRecipies = $recipieRepository->getByTagName($tag->getName(), $perPage, $perPage * ($page-1));

        return $this->render('tag/index.html.twig', [
            'recipies' => $pagedRecipies,
            'tag' => $tag,
            'page' => (int) $page,
            'totalPages' => (int) $pageCount
        ]);
    }
}
