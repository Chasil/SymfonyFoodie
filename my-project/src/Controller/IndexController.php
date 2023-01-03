<?php

namespace App\Controller;

use App\Entity\Recipie;
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
        $form = $this->createForm(AddRecipieType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $doctrineManager = $doctrine->getManager();

            if($this->getUser()) {

                /** @var UploadedFile $pictureFileName */
                $pictureFileName = $form->get('photo')->getData();
                if($pictureFileName) {
                    $originalFileName = pathinfo($pictureFileName->getClientOriginalName(), PATHINFO_FILENAME);
                    $newPhotoName = $originalFileName . '_' . uniqid() . '.' . $pictureFileName->guessExtension();
                    $pictureFileName->move('images/hosting', $newPhotoName);

                    $entityRecipy = new Recipie();
                    $entityRecipy->setName($form->get('name')->getData());
                    $entityRecipy->setDescription($form->get('description')->getData());
                    $entityRecipy->setPreparation($form->get('preparation')->getData());
                    $entityRecipy->setCategory($form->get('category')->getData());
                    $entityRecipy->setPhoto($newPhotoName);
                    $entityRecipy->setIsVisible($form->get('is_visible')->getData());
                    $entityRecipy->setUser($this->getUser());

                    $doctrineManager->persist($entityRecipy);;
                    $doctrineManager->flush();

                    $this->addFlash('notice', 'Recipy added successfully');
                }
            }
        }

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'add_recipie_form' => $form->createView()
        ]);
    }
}
