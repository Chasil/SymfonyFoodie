<?php

namespace App\Repository;

use App\Entity\Recipie;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends RecipieCollectionFieldRepository
{
    protected const className = Tag::class;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function save(Tag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function addTag(string $name, Recipie $recipie): void
    {
        $tag = $this->getEntityManager()->getRepository(Tag::class)->findOneBy(['name' => $name]);

        if (!$tag) {
            $tag = new Tag();
            $tag->setName($name);
        }
        $tag->addRecipie($recipie);
        $this->getEntityManager()->persist($tag);
    }

    public function getTagByName(string $name): Tag
    {
        $tag = $this->findOneBy(['name' => $name]);

        if (!$tag) {
            $tag = new Tag();
            $tag->setName($name);
            $this->getEntityManager()->persist($tag);
        }

        return $tag;
    }

    /**
     * @return Tag[] Returns an array of Tag objects
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
}
