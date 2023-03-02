<?php

namespace App\Repository;

use App\Entity\RecipieFieldCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class RecipieCollectionFieldRepository extends ServiceEntityRepository
{
    protected const className = '';

    public function mapFormsToEntity(array $forms): RecipieFieldCollection
    {
        $id = $forms['id']->getData();
        $name = $forms['name']->getData();

        if (!$name) {
            return $this->removeEntity($id);
        }

        if ($id) {
            /** @var RecipieFieldCollection $entity */
            $entity = $this->findOneBy(['id' => $id]);
        } else {
            /** @var RecipieFieldCollection $entity */
            $entity = new (static::className)();
            $this->getEntityManager()->persist($entity);
        }

        $entity->setName($name);

        return $entity;
    }

    private function removeEntity(int $id): RecipieFieldCollection
    {
        $entity = $this->findOneBy(['id' => $id]);
        $this->getEntityManager()->remove($entity);

        return $entity;
    }
}
