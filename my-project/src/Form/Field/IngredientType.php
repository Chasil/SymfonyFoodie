<?php

namespace App\Form\Field;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;

class IngredientType extends AbstractType implements DataMapperInterface
{

    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', HiddenType::class);
        $builder->add('name', TextType::class);
        $builder->add('measure', TextType::class);
        $builder->setDataMapper($this);
    }

    public function mapDataToForms(mixed $viewData, \Traversable $forms)
    {
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof Ingredient) {
            throw new UnexpectedTypeException($viewData, Ingredient::class);
        }

        /** @var Form $forms */
        $forms = iterator_to_array($forms);

        $forms['id']->setData($viewData->getId());
        $forms['name']->setData($viewData->getName());
        $forms['measure']->setData($viewData->getMeasure());
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        /** @var IngredientRepository $ingredientRepository */
        $ingredientRepository = $this->doctrine->getManager()->getRepository(Ingredient::class);
        $viewData = $ingredientRepository->mapFormsToIngredient($forms);
    }
}
