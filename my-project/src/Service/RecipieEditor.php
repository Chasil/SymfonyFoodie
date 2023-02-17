<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Recipie;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RecipieEditor extends AbstractController {

    public function __construct(private ManagerRegistry $doctrine, private ImageCreator $imageCreator)
    {
    }

    /**
     * @param Recipie $recipie
     * @param Form $form
     * @return void
     */
    public function prepareFormData(
        Recipie $recipie,
        Form $form
    ): void
    {
        $recipie->setName($form->get('name')->getData());
        $recipie->setDescription($form->get('description')->getData());
        $recipie->setPreparation($form->get('preparation')->getData());
        $recipie->setIsVisible($form->get('isVisible')->getData());
        $recipie->setUser($this->getUser());
    }

    /**
     * @param Recipie $recipie
     * @param UploadedFile|null $photo
     * @return bool
     */
    public function edit(Recipie $recipie, ?UploadedFile $photo): bool
    {
        $doctrineManager = $this->doctrine->getManager();
        $recipie->setUser($this->getUser());

        $newFileName = $this->imageCreator->upload($photo);

        $recipie->setPhoto($newFileName);

        $doctrineManager->persist($recipie);
        $doctrineManager->flush();

        return true;
    }
}
