<?php

namespace App\Form\Field;

use App\Entity\RecipieFieldCollection;
use App\Repository\RecipieCollectionFieldRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class RecipieCollectionFieldType extends AbstractType implements DataMapperInterface
{
    protected string $entityClassName;
    protected ManagerRegistry $doctrine;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', HiddenType::class);
        $builder->add('name', TextType::class);
        $builder->setDataMapper($this);
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms)
    {
        if ($viewData === null) {
            return;
        }

        if (!$viewData instanceof RecipieFieldCollection) {
            throw new UnexpectedTypeException($viewData, RecipieFieldCollection::class);
        }

        $forms = iterator_to_array($forms);
        $this->setFormsData($viewData, $forms);
    }

    protected function setFormsData(mixed $viewData, array $forms)
    {
        $forms['id']->setData($viewData->getId());
        $forms['name']->setData($viewData->getName());
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        /** @var RecipieCollectionFieldRepository $repository */
        $repository = $this->doctrine->getManager()->getRepository($this->entityClassName);
        $viewData = $repository->mapFormsToEntity($forms);
    }
}
