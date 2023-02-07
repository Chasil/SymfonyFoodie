<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag/{name}', name: 'tag')]
    public function index(Tag $tag): Response
    {
        return $this->render('tag/index.html.twig', [
            'recipies' => $tag->getRecipies(),
            'tag_name' => $tag->getName()
        ]);
    }
}
