<?php

namespace App\Controller;

use App\Entity\Recipie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $recipies = [];
        $searchedValue = $request->query->get('search', '');
        if ($searchedValue) {
            $doctrineManager = $doctrine->getManager();
            $recipies = $doctrineManager->getRepository(Recipie::class)->findBy(['name' => $searchedValue]);
        }

        return $this->render('search/index.html.twig', [
            'recipies' => $recipies,
            'searched_value' => $searchedValue,
        ]);
    }
}
