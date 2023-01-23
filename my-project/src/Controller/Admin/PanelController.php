<?php

namespace App\Controller\Admin;

use App\Entity\Ingredients;
use App\Entity\Recipie;
use App\Entity\Tags;
use App\Entity\Category;
use App\Form\AddRecipieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    #[Route('/panel', name: 'admin_panel')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(AddRecipieType::class);
        $form->handleRequest($request);

        $doctrineManager = $doctrine->getManager();

        if($form->isSubmitted()) {

            $apiURL = $form->get('meal_link')->getData();

            if($apiURL) {

                $jsonData = file_get_contents($apiURL);
                $responseData = json_decode($jsonData);

                if($responseData) {

                    if ($this->getUser()) {

                        foreach ($responseData as $meals) {
                            foreach ($meals as $meal) {

                                $entityRecipy = new Recipie();

                                $entityRecipy->setName($meal->strMeal);
                                $entityRecipy->setDescription("");
                                $entityRecipy->setPreparation($meal->strInstructions);
                                $entityRecipy->setPhoto($meal->strMealThumb);
                                $entityRecipy->setIsVisible(1);
                                $entityRecipy->setUser($this->getUser());

                                $entityCategory = new Category();
                                $categoryObjet = $entityCategory->setName($meal->strCategory);
                                $categoryObjet->addRecipie($entityRecipy);
                                $doctrineManager->persist($entityCategory);;

                                $tags = explode(",", $meal->strTags);

                                foreach ($tags as $tag) {
                                    $entityTags = new Tags();
                                    $tagObject = $entityTags->setName($tag);
                                    $tagObject->addRecipie($entityRecipy);
                                    $doctrineManager->persist($entityTags);
                                }

                                $ingredients = [];
                                $maxIngredients = 20;

                                for ($iterateIngredients = 1; $iterateIngredients <= $maxIngredients; $iterateIngredients++) {
                                    $ingredient = $meal->{'strIngredient' . $iterateIngredients};
                                    if (($ingredient || !empty($ingredient)) && $iterateIngredients <= $maxIngredients) {
                                        $ingredients[] = $ingredient;
                                    }
                                    $iterateIngredients++;
                                }

                                foreach ($ingredients as $ingredient) {
                                    $entityIngredients = new Ingredients();
                                    $ingredientObject = $entityIngredients->setName($ingredient);
                                    $ingredientObject->setRecipie($entityRecipy);
                                    $doctrineManager->persist($entityIngredients);
                                }

                                $doctrineManager->persist($entityRecipy);
                                $doctrineManager->flush();
                            }
                        }
                        $this->addFlash('notice', 'Saved succeeded');
                    }
                }
            }
        }

        $meals = $doctrineManager->getRepository(Recipie::class)->findAll();

        return $this->render('admin_panel/index.html.twig', [
            'controller_name' => 'PanelController',
            'add_recipie_form' => $form->createView(),
            'meals' => $meals
        ]);
    }
}
