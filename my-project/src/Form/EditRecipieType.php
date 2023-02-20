<?php
namespace App\Form;

use App\Entity\Recipie;
use App\Form\Field\CategoryTextType;
use App\Form\Field\TagTextType;
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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EditRecipieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //todo czemu category a nie categories? jak zmienię to mam błąd
        $builder
            ->add('name', TextType::class, [
                'attr' => ['maxlength' => 255],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255])
                ]
            ])
            ->add('description',TextareaType::class, [
                'attr' => ['maxlength' => 1000],
                'constraints' => [
                    new Length(['max' => 1000])
                ]
            ])
            ->add('category', CollectionType::class, [
                'data' => $options['data']->getCategory(),
                'entry_type' => CategoryTextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ]
            ])
            ->add('preparation',TextareaType::class, [
                'attr' => ['maxlength' => 10000],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 10000])
                ]
            ])
            ->add('isVisible', CheckboxType::class, [
                'constraints' => [
                    new Type('bool')
                ]
            ])
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
            ->add('tags', CollectionType::class, [
                'data' => $options['data']->getTags(),
                'entry_type' => TagTextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ]
            ])
            ->add('ingredients', CollectionType::class, [
                'data' => $options['data']->getIngredients(),
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'  => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ]
            ])
            ->add('save', SubmitType::class)
        ;

        //todo zrobić ręczne ustawienie kolejności elementów formularza
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipie::class,
        ]);
    }
}
