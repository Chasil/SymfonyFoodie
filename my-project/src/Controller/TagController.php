<?php

namespace App\Controller;

use App\Entity\Recipie;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag/{name}', name: 'tag')]
    public function index(Tag $tag): Response
    {
        $recipies = $tag->getRecipies()->getValues();

        return $this->render('tag/index.html.twig', [
            'recipies' => $recipies,
            'tag_name' => $tag->getName()
        ]);
    }
}
