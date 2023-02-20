<?php

namespace App\Controller\Admin;

use App\Entity\Recipie;
use App\Form\AddRecipieType;
use App\Form\EditRecipieType;
use App\Service\RecipieCreatorLauncher;
use App\Service\RecipieEditor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipieController extends AbstractController
{
    private const DOMAIN_NAME = 'themealdb.com';

    #[Route('/recipie', name: 'admin_recipie')]
    public function index(): Response
    {
        return $this->render('admin/recipie/index.html.twig', [
            'controller_name' => 'RecipieController',
        ]);
    }

    #[Route('/panel', name: 'add_recipie', methods: ['POST'])]
    public function saveRecipie(
        Request $request,
        RecipieCreatorLauncher $launcher
    ): Response {
        $form = $this->createForm(AddRecipieType::class);
        $form->handleRequest($request);
        $apiURL = $form->get('meal_link')->getData();

        //todo zobaczyć czy jakoś lepiej się tego fragmentu nie da zrobić
        if (str_contains($apiURL, self::DOMAIN_NAME)) {
            $apiResult = $launcher->launch(
                $apiURL,
                function() {
                    $this->addFlash('notice', 'Recipie created');
                },
                function() {
                    $this->addFlash('error', 'Recipie already exist');
                },
            );
            if (!$apiResult) {
                $this->addFlash('error', 'Recipie does not exist');
            }
        } else {
            $this->addFlash('error', 'Invalid URL domain.');
        }

        return $this->redirectToRoute('admin_panel');
    }

    #[Route('/recipie/delete/{id}', name: 'delete_recipie', methods: ['GET'])]
    public function delete(Recipie $recipie, ManagerRegistry $doctrine)
    {
        if ($this->getUser() == $recipie->getUser()) {
            $doctrine->getManager()->remove($recipie);
            $doctrine->getManager()->flush();
            $this->addFlash('deleted', 'Deleted successfully');
        } else {
            $this->addFlash('deleted', 'No User permission to delete');
        }

        return $this->redirectToRoute('admin_panel');
    }

    #[Route('/recipie/edit/{id}', name: 'edit_recipie', methods: ['GET', 'POST'])]
    public function edit(
        Recipie $recipie,
        Request $request,
        RecipieEditor $recipieEditor
    ): Response
    {
        $form = $this->createForm(EditRecipieType::class, $recipie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipieEditor->edit($recipie, $form);
            $this->addFlash('notice', 'Saved succeeded');

            return $this->redirectToRoute('edit_recipie', ['id' => $recipie->getId()]);
        }

        return $this->render('recipie/edit.html.twig', [
            'editRecipie' => $form->createView()
        ]);


    }
}
