<?php
namespace App\Form;

use App\Entity\Recipie;
use App\Form\Field\ArrayType;
use App\Form\Field\IngredientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditRecipieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $categoriesValues = $options['data']->getCategory();

        //TODO zrobić przez mapowanie jak ingredients
        $categories = [];
        foreach($categoriesValues as $categoriesValue) {
            $categories[] = $categoriesValue->getName();
        }

        $tagsValues = $options['data']->getTags();

        $tags = [];
        foreach($tagsValues as $tagsValue) {
            $tags[] = $tagsValue->getName();
        }

        $ingredients = $options['data']->getIngredients();

        $builder
            ->add('name', TextType::class, ['attr' => ['maxlength' => 255]])
            ->add('description',TextareaType::class, ['attr' => ['maxlength' => 1000]])
            ->add('category', ArrayType::class, ['mapped' => false, 'data' => $categories])
            ->add('preparation',TextareaType::class, ['attr' => ['maxlength' => 10000]])
            ->add('isVisible', CheckboxType::class)
            ->add('photo', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'File must be an image'
                    ])
                ]
            ])
            ->add('tags', ArrayType::class, ['mapped' => false, 'data' => $tags])
            ->add('ingredients', CollectionType::class, [
                'data' => $ingredients,
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false
            ])
            ->add('save', SubmitType::class)
        ;

        //todo ustyawić by domyślnie nie wyświetlało ingredients bo jest manualnie wyciągnięte w widoku
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipie::class,
        ]);
    }
}
