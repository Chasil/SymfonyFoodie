<?php

namespace App\Repository;

use App\Entity\Recipie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    private function joinCategories(QueryBuilder $query, string $categoryName): QueryBuilder
    {
        return $query
            ->join('r.categories', 'rc', 'WITH', 'rc.name = ?1')
            ->where('r.isVisible = 1')
            ->setParameter(1, $categoryName);
    }

    public function getByCategoryName(string $categoryName,  int $perPage = null, int $offset = null): array
    {
        return $this->joinCategories(
            $this->createQueryBuilder('r')
            ->setMaxResults($perPage)
            ->setFirstResult($offset),
            $categoryName
        )
        ->getQuery()
        ->execute();
    }

    public function countByCategoryName(string $categoryName): int
    {
        return $this->joinCategories(
            $this->createQueryBuilder('r')
            ->select('count(r)'),
            $categoryName
        )
        ->getQuery()
        ->getSingleScalarResult();
    }
}
