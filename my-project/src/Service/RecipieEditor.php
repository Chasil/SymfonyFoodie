<?php

namespace App\Service;

use App\Entity\Recipie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

class RecipieEditor extends AbstractController {

    public function __construct(private ManagerRegistry $doctrine, private ImageCreator $imageCreator)
    {
    }

    /**
     * @param Recipie $recipie
     * @param FormInterface $form
     * @return void
     */
    public function prepareFormData(
        Recipie $recipie,
        FormInterface $form
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
     * @param FormInterface $form
     * @return bool
     */
    public function edit(Recipie $recipie, FormInterface $form): bool
    {
        $doctrineManager = $this->doctrine->getManager();
        $recipie->setUser($this->getUser());
        $this->prepareFormData($recipie, $form);
        $newFileName = $this->imageCreator->upload($form->get('photo')->getData());
        $recipie->setPhoto($newFileName);

        $doctrineManager->persist($recipie);
        $doctrineManager->flush();

        return true;
    }
}
