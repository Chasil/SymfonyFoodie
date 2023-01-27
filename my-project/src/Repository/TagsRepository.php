<?php

namespace App\Repository;

use App\Entity\Recipie;
use App\Entity\Tags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tags>
 *
 * @method Tags|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tags|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tags[]    findAll()
 * @method Tags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tags::class);
    }

    public function save(Tags $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tags $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param string $name
     * @param Recipie $recipie
     * @return void
     */
    public function addTag(string $name, Recipie $recipie): void
    {
        $tag = $this->getEntityManager()->getRepository(Tags::class)->findOneBy(['name' => $name]);

        if(!$tag) {
            $tag = new Tags();
            $tag->setName($name);
        }
        $tag->addRecipie($recipie);
        $this->getEntityManager()->persist($tag);
    }

    /**
     * @return Tags[] Returns an array of Tags objects
     */
    public function getGrouped(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.name')
            ->groupBy('t.name')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Tags
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
