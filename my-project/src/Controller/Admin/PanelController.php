<?php

namespace App\Controller\Admin;

use App\Entity\Recipie;
use App\Form\AddRecipieType;
use App\Service\RecipieCreatorLauncher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    private const DOMAIN_NAME = 'themealdb.com';

    #[Route('/panel', name: 'admin_panel', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(AddRecipieType::class);

        return $this->render('admin_panel/index.html.twig', [
            'add_recipie_form' => $form->createView(),
            'meals' => $doctrine->getManager()->getRepository(Recipie::class)->findAll()
        ]);
    }

    #[Route('/panel', name: 'save', methods: ['POST'])]
    public function saveRecipie(
        Request $request,
        RecipieCreatorLauncher $launcher
    ): Response {
        $form = $this->createForm(AddRecipieType::class);
        $form->handleRequest($request);
        $apiURL = $form->get('meal_link')->getData();

        if(str_contains($apiURL, self::DOMAIN_NAME)) {
            $createRecipie = $launcher->launch($apiURL);
            $this->addFlash('notice', $createRecipie['created'] . ' recipies created');
            $this->addFlash('error', $createRecipie['existed'] . ' recipies exist');
        } else {
            $this->addFlash('error', 'Invalid URL domain.');
        }

        return $this->redirectToRoute('admin_panel');
    }
}
