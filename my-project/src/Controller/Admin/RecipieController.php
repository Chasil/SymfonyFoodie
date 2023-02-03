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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        if(!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

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

        if(!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(EditRecipieType::class, $recipie);

        return $this->render('recipie/edit.html.twig', [
            'edit_form' => $form->createView()
        ]);
    }

    #[Route('/recipie/edit/{id}', name: 'save_recipie', methods: ['POST'])]
    public function saveEdition(Recipie $recipie, ManagerRegistry $doctrine, Request $request): Response
    {

        $doctrineManager = $doctrine->getManager();

        $form = $this->createForm(EditRecipieType::class, $recipie);
        $form->handleRequest($request);

        //TODO przerobić to na wpólny zapis razem z zapisem z API RecipieCreator->create()

        $recipie->setName($form->get('name')->getData());
        $recipie->setDescription($form->get('description')->getData());
        $recipie->setPreparation($form->get('preparation')->getData());
        $recipie->setIsVisible($form->get('isVisible')->getData());
        $recipie->setUser($this->getUser());

        //TODO pozwolić na pominięcie dodania zdjęcia jeśli już istnieje

        /** @var UploadedFile $picture */
        $picture = $form->get('photo')->getData();
        $originalFileName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFileName .'_'. uniqid() .'.' . $picture->guessExtension();
        $picture->move('images/hosting', $newFileName);
        $recipie->setPhoto($newFileName);

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

        //TODO zrobienie w ogóle od nowa ładowania ingredients/measures w formularzu,
        // by to było powiązane 1:1 bo obecne rozwiązanie jest z dupy

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
            $ingredient = $ingredientRepository->getIngredientByName($formIngredient, '1 gram');
            $recipie->addIngredient($ingredient);
        }

        $doctrineManager->persist($recipie);
        $doctrineManager->flush();

        return $this->redirectToRoute('edit_recipie', ['id' => $recipie->getId()]);
    }

    // TODO Przenieść do osobnego serwisu, bo obecnie jest 2x
    /**
     * @param string $string
     * @return array
     */
    public function splitItemsToArray(string $items): array
    {
        return array_map(
            'trim',
            explode(",", $items)
        );
    }
}
