<?php

namespace App\Controller;

use App\Entity\Recipie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        $doctrineManager = $doctrine->getManager();
        $meals = $doctrineManager->getRepository(Recipie::class)->findBy(['is_visible' => 1]);

        return $this->render('index/index.html.twig', [
            'meals' => $meals
        ]);
    }
}
