<?php

namespace App\Form\Field;

use App\Entity\Ingredient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class IngredientType extends RecipieCollectionFieldType
{
    public function __construct(protected ManagerRegistry $doctrine)
    {
        $this->entityClassName = Ingredient::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('measure', TextType::class);
    }

    protected function setFormsData(mixed $viewData, array $forms)
    {
        parent::setFormsData($viewData, $forms);
        $forms['measure']->setData($viewData->getMeasure());
    }
}
