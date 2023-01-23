<?php

namespace App\Controller\Admin;

use App\Entity\Ingredients;
use App\Entity\Recipie;
use App\Entity\Tags;
use App\Form\EditRecipieType;
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
    public function delete(int $id, ManagerRegistry $doctrine) {

        if(!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $doctrineManager = $doctrine->getManager();
        $recipie = $doctrineManager->getRepository(Recipie::class)->find($id);

        if($this->getUser() == $recipie->getUser()) {
            $doctrineManager->remove($recipie);
            $doctrineManager->flush();
            $this->addFlash('deleted', 'Deleted successfully');
        }

        return $this->redirectToRoute('admin_panel');
    }

    #[Route('/recipie/edit/{id}', name: 'edit_recipie')]
    public function edit(int $id, ManagerRegistry $doctrine, Request $request): Response {

        if(!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $doctrineManager = $doctrine->getManager();
        $recipie = $doctrineManager->getRepository(Recipie::class)->find($id);

        $form = $this->createForm(EditRecipieType::class, $recipie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $this->getUser()) {
            $recipie->setName($form->get('name')->getData());
            $recipie->setDescription($form->get('description')->getData());
            $recipie->setCategory($form->get('category')->getData());
            $recipie->setPreparation($form->get('preparation')->getData());
            $recipie->setIsVisible($form->get('isVisible')->getData());
            $recipie->setPhoto($form->get('photo')->getData());
            $recipie->setUser($this->getUser());

            $tags = $doctrineManager->getRepository(Tags::class)->findBy(['name' => $id]);

            foreach ($tags as $tag) {
                $doctrineManager->remove($tag);
            }

            $formTags = explode(",", str_replace(' ', '', $form->get('tags')->getData()));

            foreach($formTags as $tag) {
                $entityTags = new Tags();
                $tagObject = $entityTags->setName($tag);
                $tagObject->addRecipie($recipie);
                $doctrineManager->persist($entityTags);
            }

            $ingredients = $doctrineManager->getRepository(Ingredients::class)->findBy(['name' => $id]);

            foreach($ingredients as $ingredient) {
                $doctrineManager->remove($ingredient);
            }

            $formIngredients = explode(",", str_replace(' ', '', $form->get('ingredients')->getData()));

            foreach($formIngredients as $ingredient) {
                $entityIngredients = new Ingredients();
                $ingredientObject = $entityIngredients->setName($ingredient);
                $ingredientObject->setRecipie($recipie);
                $doctrineManager->persist($entityIngredients);
            }

            $doctrineManager->flush();
        }

        $recipie = $doctrineManager->getRepository(Recipie::class)->find($id);

        return $this->render('recipie/edit.html.twig', [
            'edit_form' => $form->createView(),
            'meal' => $recipie
        ]);
    }
}
