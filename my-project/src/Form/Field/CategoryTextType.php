<?php

namespace App\Form\Field;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;

class CategoryTextType extends RecipieCollectionFieldType
{
    public function __construct(protected ManagerRegistry $doctrine)
    {
        $this->entityClassName = Category::class;
    }
}
