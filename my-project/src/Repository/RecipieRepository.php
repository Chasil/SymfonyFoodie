<?php

namespace App\Repository;

use App\Entity\Recipie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipie>
 *
 * @method Recipie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipie[]    findAll()
 * @method Recipie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipie::class);
    }

    public function save(Recipie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
