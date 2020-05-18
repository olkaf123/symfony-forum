<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\CommentMark;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentMark[]    findAll()
 * @method CommentMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentMarkRepository extends ServiceEntityRepository
{
    /**
     * CommentMarkRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentMark::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\CommentMark $mark CommentMark entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(CommentMark $mark): void
    {
        $this->_em->persist($mark);
        $this->_em->flush($mark);
    }

    /**
     * @param Comment $comment
     * @return int|mixed|string
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countMarkValue(Comment $comment)
    {
        $result = $this->getOrCreateQueryBuilder()
            ->select('SUM(mark.mark)')
            ->andWhere('mark.comment = :val')
            ->setParameter('val', $comment)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $result ? $result : 0;
    }

    /**
     * @param Comment $comment Comment
     * @param User    $user    User
     * @return boolean true|false
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function alreadyVoted(Comment $comment, User $user = null)
    {
        if (!$user) {
            return true;
        }

        $result = $this->getOrCreateQueryBuilder()
            ->select('COUNT(mark.id)')
            ->andWhere('mark.comment = :comment')
            ->andWhere('mark.user = :user')
            ->setParameter('comment', $comment)
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return (bool)$result;
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('mark');
    }
}
