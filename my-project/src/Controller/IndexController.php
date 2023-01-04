<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Entity\Recipie;
use App\Entity\Tags;
use App\Form\AddRecipieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        $apiURL = 'https://www.themealdb.com/api/json/v1/1/search.php?s=Arrabiata';
        $jsonData = file_get_contents($apiURL);
        $responseData = json_decode($jsonData);

        $form = $this->createForm(AddRecipieType::class);

        $form->handleRequest($request);
        if($responseData) {

            $doctrineManager = $doctrine->getManager();

            if($this->getUser()) {

                foreach($responseData as $meals) {
                    foreach($meals as $meal) {

                        $entityRecipy = new Recipie();

                        $entityRecipy->setName($meal->strMeal);
                        $entityRecipy->setDescription("");
                        $entityRecipy->setPreparation($meal->strInstructions);
                        $entityRecipy->setCategory($meal->strCategory);
                        $entityRecipy->setPhoto($meal->strMealThumb);
                        $entityRecipy->setIsVisible(1);
                        $entityRecipy->setUser($this->getUser());

                        $tags = explode(",", $meal->strTags);

                        foreach($tags as $tag) {
                            $entityTags = new Tags();
                            $tagObject = $entityTags->setName($tag);
                            $tagObject->setRecipie($entityRecipy);
                            $doctrineManager->persist($entityTags);
                        }

                        $ingredients = [];
                        $maxIngredients = 20;

                        for($iterateIngredients = 1; $iterateIngredients <= $maxIngredients; $iterateIngredients++) {
                            $ingredient = $meal->{'strIngredient' . $iterateIngredients};
                            if(($ingredient || !empty($ingredient)) && $iterateIngredients <= $maxIngredients) {
                                $ingredients[] = $ingredient;
                            }
                            $iterateIngredients++;
                        }

                        foreach($ingredients as $ingredient) {
                            $entityIngredients = new Ingredients();
                            $ingredientObject = $entityIngredients->setName($ingredient);
                            $ingredientObject->setRecipie($entityRecipy);
                            $doctrineManager->persist($entityIngredients);
                        }

                        $doctrineManager->persist($entityRecipy);
                        $doctrineManager->flush();

                    }
                }


                $this->addFlash('notice', 'Recipy added successfully');

            }
        }

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'add_recipie_form' => $form->createView()
        ]);
    }
}
