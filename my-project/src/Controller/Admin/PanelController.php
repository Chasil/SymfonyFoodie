<?php

namespace App\Controller\Admin;

use App\Entity\Recipie;
use App\Form\AddRecipieType;
use App\Serializer\RecipieCollection;
use App\Service\RecipieCreator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
    public function saveRecipie(
        Request $request,
        RecipieCreator $recipieCreator,
        SerializerInterface $serializer
    ): Response
    {

        $form = $this->createForm(AddRecipieType::class);
        $form->handleRequest($request);
        $apiURL = $form->get('meal_link')->getData();

        if($apiURL) {

            $jsonData = file_get_contents($apiURL);

            /** @var RecipieCollection $recipieCollection */
            $recipieCollection = $serializer->deserialize($jsonData, RecipieCollection::class, 'json');

            if ($this->getUser()) {
                foreach ($recipieCollection->getMeals() as $recipie) {
                    $recipieCreator->prepareIngredients($recipie, $recipie->getDeserializedIngredients());
                    $recipieCreator->create($recipie);
                }
                $this->addFlash('notice', 'Saved succeeded');
            }
        }

        return $this->redirectToRoute('admin_panel');
    }
}
