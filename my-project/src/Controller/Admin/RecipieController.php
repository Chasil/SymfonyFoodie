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
        $doctrineManager = $doctrine->getManager();

        if ($this->getUser() == $recipie->getUser()) {
            $doctrineManager->remove($recipie);
            $doctrineManager->flush();
            $this->addFlash('deleted', 'Deleted successfully');
        }

        return $this->redirectToRoute('admin_panel');
    }

    #[Route('/recipie/edit/{id}', name: 'edit_recipie', methods: ['GET'])]
    public function edit(Recipie $recipie): Response
    {
        $form = $this->createForm(EditRecipieType::class, $recipie);

        return $this->render('recipie/edit.html.twig', [
            'editRecipie' => $form->createView()
        ]);
    }

    #[Route('/recipie/edit/{id}', name: 'save_recipie', methods: ['POST'])]
    public function saveEdition(
        Recipie $recipie,
        Request $request,
        RecipieEditor $recipieEditor
    ): Response
    {
        $form = $this->createForm(EditRecipieType::class, $recipie);
        $form->handleRequest($request);

        $recipieEditor->prepareFormData($recipie, $form);
        $recipieEditor->prepareCategories($recipie, $form->get('category')->getData());
        $recipieEditor->prepareTags($recipie, $form->get('tags')->getData());

        $recipieEditor->edit($recipie, $form->get('photo')->getData());

        $this->addFlash('notice', 'Saved succeeded');

        return $this->redirectToRoute('edit_recipie', ['id' => $recipie->getId()]);
    }
}
