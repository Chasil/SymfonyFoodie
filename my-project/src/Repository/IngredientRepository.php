<?php

namespace App\Repository;

use App\Entity\Ingredient;
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
class IngredientRepository extends ServiceEntityRepository
{
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

    public function mapFormsToIngredient(array $forms): Ingredient
    {
        $id = $forms['id']->getData();
        $name = $forms['name']->getData();
        $measure = $forms['measure']->getData();

        if ($id) {
            $viewData = $this->findOneBy(['id' => $id]);
        } else {
            $viewData = new Ingredient();
            $this->getEntityManager()->persist($viewData);
        }

        if(!$name) {
            $this->getEntityManager()->remove($viewData);
        } else {
            $viewData->setName($name);
            $viewData->setMeasure($measure);
        }

        return $viewData;
    }
}
