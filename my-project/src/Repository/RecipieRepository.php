<?php

namespace App\Repository;

use App\Entity\Recipie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function queryAll(): Query
    {
        return $this->createQueryBuilder('r')
        ->select('r')
            ->where('r.isVisible = 1')
        ->getQuery();
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

    public function queryByCategoryName(string $categoryName): Query
    {
        return $this->joinCategories(
            $this->createQueryBuilder('r')
            ->select('r'),
            $categoryName
        )
        ->getQuery();
    }

    private function joinTags(QueryBuilder $query, string $tagName): QueryBuilder
    {
        return $query
            ->join('r.tags', 'rt', 'WITH', 'rt.name = ?1')
            ->where('r.isVisible = 1')
            ->setParameter(1, $tagName);
    }

    public function getByTagName(string $tagName,  int $perPage = null, int $offset = null): array
    {
        return $this->joinTags(
            $this->createQueryBuilder('r')
                ->setMaxResults($perPage)
                ->setFirstResult($offset),
            $tagName
        )
            ->getQuery()
            ->execute();
    }

    public function queryByTagName(string $tagName): Query
    {
        return $this->joinTags(
            $this->createQueryBuilder('r')
                ->select('r'),
            $tagName
        )
        ->getQuery();
    }
}
