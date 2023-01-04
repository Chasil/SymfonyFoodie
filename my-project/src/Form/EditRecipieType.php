<?php

namespace App\Form;

use App\Entity\Recipie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditRecipieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $tagsValues = $options['data']->getTags()->getValues();

        $tags = [];
        foreach($tagsValues as $tagsValue) {
            $tags[] = $tagsValue->getName();
        }

        $ingredientsValues = $options['data']->getIngredients()->getValues();

        $ingredients = [];
        foreach($ingredientsValues as $ingredientsValue) {
            $ingredients[] = $ingredientsValue->getName();
        }

        $builder
            ->add('name')
            ->add('description')
            ->add('category')
            ->add('preparation')
            ->add('isVisible')
            ->add('photo')
            ->add('tags', TextType::class, ['mapped' => false, 'data' => implode(",", $tags)])
            ->add('ingredients', TextType::class, ['mapped' => false, 'data' => implode(",", $ingredients)])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipie::class,
        ]);
    }
}
