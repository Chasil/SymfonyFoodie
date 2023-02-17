<?php

namespace App\Form\Field;

use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;

class TagTextType extends RecipieCollectionFieldType
{
    public function __construct(protected ManagerRegistry $doctrine)
    {
        $this->entityClassName = Tag::class;
    }
}
