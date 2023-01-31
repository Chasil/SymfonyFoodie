<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipie;
use App\Entity\Tag;
use App\Form\EditRecipieType;
use App\Repository\IngredientRepository;
use App\Repository\TagRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
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

    #[Route('/recipie/edit/{id}', name: 'edit_recipie', methods: ['GET'])]
    public function edit(int $id, ManagerRegistry $doctrine, Request $request): Response {

        if(!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $doctrineManager = $doctrine->getManager();

        /** @var Recipie $recipie */
        $recipie = $doctrineManager->getRepository(Recipie::class)->find($id);
        $form = $this->createForm(EditRecipieType::class, $recipie);

        return $this->render('recipie/edit.html.twig', [
            'edit_form' => $form->createView()
        ]);
    }

    #[Route('/recipie/edit/{id}', name: 'save_recipie', methods: ['POST'])]
    public function saveEdition(int $id, ManagerRegistry $doctrine, Request $request): Response {

        $doctrineManager = $doctrine->getManager();

        /** @var Recipie $recipie */
        $recipie = $doctrineManager->getRepository(Recipie::class)->find($id);

        $form = $this->createForm(EditRecipieType::class, $recipie);
        $form->handleRequest($request);

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

        $formCategories = $this->splitItemsToArray($form->get('category')->getData());

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

        $formTags = $this->splitItemsToArray($form->get('tags')->getData());

        $recipieTags = $recipie->getTags();

        foreach($recipieTags as $recipieTag) {
            $name = $recipieTag->getName();
            if(!in_array($name, $formTags)) {
                $recipie->removeTag($recipieTag);
            }
        }

        foreach($formTags as $formTag) {
            /** @var TagRepository $tagRepository */
            $tagRepository = $doctrineManager->getRepository(Tag::class);
            $tag = $tagRepository->getTagByName($formTag);
            $recipie->addTag($tag);
        }

        $formIngredients = $this->splitItemsToArray($form->get('ingredients')->getData());

        $recipieIngredients = $recipie->getIngredients();

        foreach($recipieIngredients as $recipieIngredient) {
            $name = $recipieIngredient->getName();
            if(!in_array($name, $formIngredients)) {
                $recipie->removeIngredient($recipieIngredient);
            }
        }

        foreach($formIngredients as $formIngredient) {
            /** @var IngredientRepository $ingredientRepository */
            $ingredientRepository = $doctrineManager->getRepository(Ingredient::class);
            //TODO trzeba dorobić miary dla składników i przekazać je do poniższej funkcji i w niej zapisać
            $ingredient = $ingredientRepository->getIngredientByName($formIngredient);
            $recipie->addIngredient($ingredient);
        }

        $doctrineManager->persist($recipie);
        $doctrineManager->flush();

        return $this->redirectToRoute('edit_recipie', ['id' => $id]);
    }

    /**
     * @param string $string
     * @return array
     */
    public function splitItemsToArray(string $items): array {
        return array_map(
            'trim',
            explode(",", $items)
        );
    }
}
