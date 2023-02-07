<?php

namespace App\Form\Field;

use App\HandleArrayInput;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ArrayType extends TextType
{
    use HandleArrayInput;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this);
    }

    public function transform(mixed $data): mixed
    {
        return $this->transformArrayToString($data);
    }

    public function reverseTransform(mixed $data): mixed
    {
        return $this->transformStringToArray($data);
    }
}
