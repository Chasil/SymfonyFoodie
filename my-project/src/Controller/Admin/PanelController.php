<?php

namespace App\Controller\Admin;

use App\Entity\Ingredients;
use App\Entity\Recipie;
use App\Entity\Tag;
use App\Entity\Category;
use App\Form\AddRecipieType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    #[Route('/panel', name: 'admin_panel', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(AddRecipieType::class);

        $doctrineManager = $doctrine->getManager();

        return $this->render('admin_panel/index.html.twig', [
            'add_recipie_form' => $form->createView(),
            'meals' => $doctrineManager->getRepository(Recipie::class)->findAll()
        ]);
    }

    #[Route('/panel', name: 'save', methods: ['POST'])]
    public function saveRecipie(Request $request, ManagerRegistry $doctrine): Response {

        $form = $this->createForm(AddRecipieType::class);
        $form->handleRequest($request);
        $apiURL = $form->get('meal_link')->getData();
        $doctrineManager = $doctrine->getManager();

        if($apiURL) {

            $jsonData = file_get_contents($apiURL);
            $responseData = json_decode($jsonData);

            if($responseData) {

                if ($this->getUser()) {

                    foreach ($responseData as $meals) {
                        foreach ($meals as $meal) {

//                            $recipieCreator = new RecipieCreator();
//                            $recipieCreator->create([
//                                'name' => $meal->strMeal,
//                            ]);
                            $entityRecipy = new Recipie();

                            $entityRecipy->setName($meal->strMeal);
                            $entityRecipy->setDescription("");
                            $entityRecipy->setPreparation($meal->strInstructions);
                            $entityRecipy->setPhoto($meal->strMealThumb);
                            $entityRecipy->setIsVisible(1);
                            $entityRecipy->setUser($this->getUser());

                            /** @var CategoryRepository $categoryRepository */
                            $categoryRepository = $doctrineManager->getRepository(Category::class);
                            $category = $categoryRepository->getCategoryByName($meal->strCategory);
                            $entityRecipy->addCategory($category);

                            $tags = explode(",", $meal->strTags);

                            foreach ($tags as $tag) {
                                $doctrineManager->getRepository(Tag::class)->addTag($tag, $entityRecipy);
                            }

                            $ingredients = [];
                            $maxIngredients = 20;

                            for ($iterateIngredients = 1; $iterateIngredients <= $maxIngredients; $iterateIngredients++) {
                                $ingredient = $meal->{'strIngredient' . $iterateIngredients};
                                if (($ingredient || !empty($ingredient)) && $iterateIngredients <= $maxIngredients) {
                                    $ingredients[$iterateIngredients]['name'] = $ingredient;
                                    $ingredients[$iterateIngredients]['measure'] = $meal->{'strMeasure' . $iterateIngredients};
                                }
                                $iterateIngredients++;
                            }

                            foreach ($ingredients as $ingredient) {
                                $entityIngredient = new Ingredients();
                                $entityIngredient->setName($ingredient['name']);
                                $entityIngredient->setMeasure($ingredient['measure']);
                                $entityIngredient->setRecipie($entityRecipy);
                                $doctrineManager->persist($entityIngredient);
                            }

                            $doctrineManager->persist($entityRecipy);
                            $doctrineManager->flush();
                        }
                    }
                    $this->addFlash('notice', 'Saved succeeded');
                }
            }
        }

        return $this->redirectToRoute('admin_panel');
    }
}
