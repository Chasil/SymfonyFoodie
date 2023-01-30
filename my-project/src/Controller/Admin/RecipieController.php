<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipie;
use App\Entity\Tag;
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

        /** @var Recipie $recipie */
        $recipie = $doctrineManager->getRepository(Recipie::class)->find($id);

        $form = $this->createForm(EditRecipieType::class, $recipie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $this->getUser()) {
//            $recipieCreator = new RecipieCreator();
//            $recipieCreator->create([
//                'name' => $form->get('name')->getData(),
//            ]);
            $recipie->setName($form->get('name')->getData());
            $recipie->setDescription($form->get('description')->getData());
            $recipie->setPreparation($form->get('preparation')->getData());
            $recipie->setIsVisible($form->get('isVisible')->getData());
            $recipie->setPhoto($form->get('photo')->getData());
            $recipie->setUser($this->getUser());

            $formCategories = array_map(
                'trim',
                explode(",", $form->get('category')->getData())
            );
            $recipeCategories = $recipie->getCategory();
            foreach ($recipeCategories as $recipeCategory) {
                $name = $recipeCategory->getName();
                if (!in_array($name, $formCategories)) {
                    $recipie->removeCategory($recipeCategory);
                }
            }

            foreach($formCategories as $formCategory) {
                /** @var CategoryRepository $categoryRepository */
                $categoryRepository = $doctrineManager->getRepository(Category::class);
                $category = $categoryRepository->getCategoryByName($formCategory);
                $recipie->addCategory($category);
            }

            $tags = $doctrineManager->getRepository(Tag::class)->findBy(['name' => $id]);

            foreach ($tags as $tag) {
                $doctrineManager->remove($tag);
            }

            $formTags = explode(",", str_replace(' ', '', $form->get('tags')->getData()));

            foreach($formTags as $tag) {
                $entityTags = new Tag();
                $tagObject = $entityTags->setName($tag);
                $tagObject->addRecipie($recipie);
                $doctrineManager->persist($entityTags);
            }

            $ingredients = $doctrineManager->getRepository(Ingredient::class)->findBy(['name' => $id]);

            foreach($ingredients as $ingredient) {
                $doctrineManager->remove($ingredient);
            }

            $formIngredients = explode(",", str_replace(' ', '', $form->get('ingredients')->getData()));

            foreach($formIngredients as $ingredient) {
                $entityIngredients = new Ingredient();
                $ingredientObject = $entityIngredients->setName($ingredient);
                $entityIngredients->setMeasure('asd');
                $ingredientObject->setRecipie($recipie);
                $doctrineManager->persist($entityIngredients);
            }

            $doctrineManager->persist($recipie);
            $doctrineManager->flush();
        }

        $recipie = $doctrineManager->getRepository(Recipie::class)->find($id);

        return $this->render('recipie/edit.html.twig', [
            'edit_form' => $form->createView(),
            'meal' => $recipie
        ]);
    }
}
