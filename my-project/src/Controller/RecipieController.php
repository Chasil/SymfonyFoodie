<?php

namespace App\Controller;

use App\Entity\Recipie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipieController extends AbstractController
{
    #[Route('/recipie/{id}', name: 'recipie')]
    public function index(Recipie $recipie): Response
    {
        return $this->render('recipie/recipie.html.twig', [
            'recipie' => $recipie,
            'tags' => $recipie->getTags(),
            'ingredients' => $recipie->getIngredients()
        ]);
    }

}