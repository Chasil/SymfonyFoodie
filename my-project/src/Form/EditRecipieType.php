<?php
namespace App\Form;

use App\Entity\Recipie;
use App\Form\Field\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

        $categories = [];
        foreach($categoriesValues as $categoriesValue) {
            $categories[] = $categoriesValue->getName();
        }

        $tagsValues = $options['data']->getTags();

        $tags = [];
        foreach($tagsValues as $tagsValue) {
            $tags[] = $tagsValue->getName();
        }

        $ingredientsValues = $options['data']->getIngredients();

        $ingredients = [];
        foreach($ingredientsValues as $key => $ingredientsValue) {
            $ingredients['name'][$key] = $ingredientsValue->getName();
            $ingredients['measure'][$key] = $ingredientsValue->getMeasure();
        }

        $builder
            ->add('name', TextType::class, ['attr' => ['maxlength' => 255]])
            ->add('description',TextareaType::class, ['attr' => ['maxlength' => 1000]])
            ->add('category', ArrayType::class, ['mapped' => false, 'data' => $categories])
            // UP: Napisałeś, że lepszy byłby EntityType czyli forma wyboru z listy - to jednak zaprzecza zasadzie
            // kategorii w tym przypadku, poniewaz zawsze pierwsza kategorie zaciagam z API, a potem daję
            // możliwość dodawania swoich z uwagi na to, że nie mam osobnego zarządzania kategoriami
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
            ->add('ingredients', ArrayType::class, ['mapped' => false, 'data' => $ingredients['name']])
            ->add('measure', ArrayType::class, ['mapped' => false, 'data' => $ingredients['measure']])
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
