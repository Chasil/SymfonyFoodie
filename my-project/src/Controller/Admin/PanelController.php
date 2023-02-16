<?php

namespace App\Controller\Admin;

use App\Entity\Recipie;
use App\Form\AddRecipieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    #[Route('/panel', name: 'admin_panel', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(AddRecipieType::class);

        return $this->render('admin_panel/index.html.twig', [
            'add_recipie_form' => $form->createView(),
            'meals' => $doctrine->getManager()->getRepository(Recipie::class)->findAll()
        ]);
    }
}
