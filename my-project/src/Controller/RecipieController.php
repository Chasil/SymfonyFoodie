<?php

namespace App\Controller;

use App\Entity\Recipie;
use App\Form\EditRecipieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('recipie/edit.html.twig', [
            'edit_form' => $form->createView(),
            'meal' => $meal
        ]);
    }

}