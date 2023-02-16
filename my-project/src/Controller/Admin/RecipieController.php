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

    //TODO przenieść do RecipieController
    #[Route('/panel', name: 'add_recipie', methods: ['POST'])]
    public function saveRecipie(
        Request $request,
        RecipieCreatorLauncher $launcher
    ): Response {
        $form = $this->createForm(AddRecipieType::class);
        $form->handleRequest($request);
        $apiURL = $form->get('meal_link')->getData();

        if(str_contains($apiURL, self::DOMAIN_NAME)) {
            $launcher->launch(
                $apiURL,
                function() {
                    $this->addFlash('notice', 'Recipie created');
                },
                function() {
                    $this->addFlash('error', 'Recipie already exist');
                },
            );
        } else {
            $this->addFlash('error', 'Invalid URL domain.');
        }

        return $this->redirectToRoute('admin_panel');
    }

    #[Route('/recipie/delete/{id}', name: 'delete_recipie', methods: ['GET'])]
    public function delete(Recipie $recipie, ManagerRegistry $doctrine)
    {
        $doctrineManager = $doctrine->getManager();

        if($this->getUser() == $recipie->getUser()) {
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

        //TODO podzielić na metody w serwisie RecipieEditor
        $recipie->setName($form->get('name')->getData());
        $recipie->setDescription($form->get('description')->getData());
        $recipie->setPreparation($form->get('preparation')->getData());
        $recipie->setIsVisible($form->get('isVisible')->getData());
        $recipie->setUser($this->getUser());
        $photo = $form->get('photo')->getData();

        $formCategories = $form->get('category')->getData();
        $categories = $recipie->getCategory();
        $recipieEditor->removeRepetitions($recipie, $categories, $formCategories, 'Category');
        $recipieEditor->prepareCategories($recipie, $formCategories);

        $formTags = $form->get('tags')->getData();
        $tags = $recipie->getTags();
        $recipieEditor->removeRepetitions($recipie, $tags, $formTags, 'Tag');
        $recipieEditor->prepareTags($recipie, $formTags);

//        $formIngredients = $form->get('ingredients')->getData();
//        $ingredients = $recipie->getIngredients();
//        $recipieEditor->removeRepetitions($recipie, $ingredients, $formIngredients, 'Ingredient');
//        $ingredientsWithMeasures = array_combine($formIngredients, $formMeasures);
//        $recipieEditor->prepareIngredients($recipie, $ingredientsWithMeasures);

        $recipieEditor->edit($recipie, $photo);

        $this->addFlash('notice', 'Saved succeeded');

        return $this->redirectToRoute('edit_recipie', ['id' => $recipie->getId()]);
    }
}
