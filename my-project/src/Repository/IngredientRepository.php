<?php

namespace App\Repository;

use App\Entity\Ingredient;
use App\Entity\RecipieFieldCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends RecipieCollectionFieldRepository
{
    protected const className = Ingredient::class;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function save(Ingredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ingredient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getIngredientByName(string $name, string $measure): Ingredient
    {
        $ingredient = $this->findOneBy(['name' => $name]);

        if (!$ingredient) {
            $ingredient = new Ingredient();
            $ingredient->setName($name);
            $ingredient->setMeasure($measure);
            $this->getEntityManager()->persist($ingredient);
        }
        return $ingredient;
    }

    public function mapFormsToEntity(array $forms): RecipieFieldCollection
    {
        /** @var Ingredient $entity */
        $entity = parent::mapFormsToEntity($forms);
        $measure = $forms['measure']->getData();
        $entity->setMeasure($measure);

        return $entity;
    }

}
