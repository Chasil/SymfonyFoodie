<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipie;
use App\Entity\Tag;
use App\Form\EditRecipieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipieController extends AbstractController
{
    #[Route('/recipie/{id}', name: 'recipie')]
    public function index(int $id, ManagerRegistry $doctrine): Response {

        if(!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $doctrineManager = $doctrine->getManager();
        /** @var Recipie $recipie */
        $recipie = $doctrineManager->getRepository(Recipie::class)->find($id);
        $tags = $recipie->getTags()->getValues();
        $ingredients = $recipie->getIngredients()->getValues();

        return $this->render('recipie/recipie.html.twig', [
            'recipie' => $recipie,
            'tags' => $tags,
            'ingredients' => $ingredients
        ]);
    }

}