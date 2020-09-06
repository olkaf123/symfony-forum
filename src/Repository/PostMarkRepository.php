<?php
/**
 * Post mark repository
 */

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostMark;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostMark[]    findAll()
 * @method PostMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostMarkRepository extends ServiceEntityRepository
{
    /**
     * PostMarkRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostMark::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\PostMark $mark PostMark entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(PostMark $mark): void
    {
        $this->_em->persist($mark);
        $this->_em->flush($mark);
    }

    /**
     * @param Post $post
     *
     * @return int|mixed|string
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countMarkValue(Post $post)
    {
        $result = $this->getOrCreateQueryBuilder()
            ->select('SUM(mark.mark)')
            ->andWhere('mark.post = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? $result : 0;
    }

    /**
     * @param Post $post Post
     * @param User $user User
     *
     * @return boolean true|false
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function alreadyVoted(Post $post, User $user = null)
    {
        if (!$user) {
            return true;
        }

        $result = $this->getOrCreateQueryBuilder()
            ->select('COUNT(mark.id)')
            ->andWhere('mark.post = :post')
            ->andWhere('mark.user = :user')
            ->setParameter('post', $post)
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return (bool) $result;
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('mark');
    }
}
