<?php

namespace App\Service;

use App\Entity\Recipie;
use App\Exception\ImageUploadFailure;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

class RecipieEditor extends AbstractController {

    public function __construct(
        private ManagerRegistry $doctrine,
        private ImageCreator $imageCreator,
        private LoggerInterface $logger
    )
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
    public function edit(
        Recipie $recipie,
        FormInterface $form): bool
    {
        $doctrineManager = $this->doctrine->getManager();
        $recipie->setUser($this->getUser());
        $this->prepareFormData($recipie, $form);

        try {
            $newFileName = $this->imageCreator->upload($form->get('photo')->getData());
            $recipie->setPhoto($newFileName);
        } catch (ImageUploadFailure $exception) {
            $this->addFlash('error', $exception->getMessage());
            $this->logger->log('ERROR', $exception);
        }

        $doctrineManager->persist($recipie);
        $doctrineManager->flush();

        return true;
    }
}
