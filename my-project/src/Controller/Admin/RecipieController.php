<?php

namespace App\Controller\Admin;

use App\Entity\Recipie;
use App\Form\EditRecipieType;
use App\Service\Manager;
use App\Service\RecipieEditor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipieController extends AbstractController
{
    #[Route('/recipie', name: 'admin_recipie')]
    public function index(): Response
    {
        return $this->render('admin/recipie/index.html.twig', [
            'controller_name' => 'RecipieController',
        ]);
    }

    #[Route('/recipie/delete/{id}', name: 'delete_recipie')]
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
    public function edit(Recipie $recipie, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(EditRecipieType::class, $recipie);

        return $this->render('recipie/edit.html.twig', [
            'edit_form' => $form->createView()
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

        //TODO zrobienie w ogóle od nowa ładowania ingredients/measures w formularzu,
        // by to było powiązane 1:1 bo obecne rozwiązanie jest z dupy

        $formIngredients = $form->get('ingredients')->getData();
        $formMeasures = $form->get('measure')->getData();
        $ingredients = $recipie->getIngredients();
        $recipieEditor->removeRepetitions($recipie, $ingredients, $formIngredients, 'Ingredient');
        $ingredientsWithMeasures = array_combine($formIngredients, $formMeasures);
        $recipieEditor->prepareIngredients($recipie, $ingredientsWithMeasures);

        $recipieEditor->edit($recipie, $photo);

        $this->addFlash('notice', 'Saved succeeded');

        return $this->redirectToRoute('edit_recipie', ['id' => $recipie->getId()]);
    }
}
