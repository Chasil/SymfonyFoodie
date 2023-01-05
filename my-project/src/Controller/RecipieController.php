<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Entity\Recipie;
use App\Entity\Tags;
use App\Form\EditRecipieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecipieController extends AbstractController
{
    #[Route('/recipie/{id}', name: 'recipie')]
    public function index() {

    }

    #[Route('/recipie/delete/{id}', name: 'delete_recipie')]
    public function delete(int $id, ManagerRegistry $doctrine) {

        $doctrineManager = $doctrine->getManager();
        $meal = $doctrineManager->getRepository(Recipie::class)->find($id);

        if($this->getUser() == $meal->getUser()) {
            $doctrineManager->remove($meal);
            $doctrineManager->flush();
            $this->addFlash('deleted', 'Deleted successfully');
        }

        return $this->redirectToRoute('app_admin_panel');
    }

    #[Route('/recipie/edit/{id}', name: 'edit_recipie')]
    public function edit(int $id, ManagerRegistry $doctrine, Request $request) {

        $doctrineManager = $doctrine->getManager();
        $meal = $doctrineManager->getRepository(Recipie::class)->find($id);

        $form = $this->createForm(EditRecipieType::class, $meal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $this->getUser()) {
            $meal->setName($form->get('name')->getData());
            $meal->setDescription($form->get('description')->getData());
            $meal->setCategory($form->get('category')->getData());
            $meal->setPreparation($form->get('preparation')->getData());
            $meal->setIsVisible($form->get('isVisible')->getData());
            $meal->setPhoto($form->get('photo')->getData());
            $meal->setUser($this->getUser());

            $tags = $doctrineManager->getRepository(Tags::class)->findBy(['recipie' => $id]);

            foreach ($tags as $tag) {
                $doctrineManager->remove($tag);
            }

            $formTags = explode(",", str_replace(' ', '', $form->get('tags')->getData()));

            foreach($formTags as $tag) {
                $entityTags = new Tags();
                $tagObject = $entityTags->setName($tag);
                $tagObject->setRecipie($meal);
                $doctrineManager->persist($entityTags);
            }

            $ingredients = $doctrineManager->getRepository(Ingredients::class)->findBy(['recipie' => $id]);

            foreach($ingredients as $ingredient) {
                $doctrineManager->remove($ingredient);
            }

            $formIngredients = explode(",", str_replace(' ', '', $form->get('ingredients')->getData()));

            foreach($formIngredients as $ingredient) {
                $entityIngredients = new Ingredients();
                $ingredientObject = $entityIngredients->setName($ingredient);
                $ingredientObject->setRecipie($meal);
                $doctrineManager->persist($entityIngredients);
            }

            $doctrineManager->flush();
        }

        $meal = $doctrineManager->getRepository(Recipie::class)->find($id);

        return $this->render('recipie/edit.html.twig', [
            'edit_form' => $form->createView(),
            'meal' => $meal
        ]);
    }

}