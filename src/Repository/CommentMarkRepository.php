<?php

namespace App\Repository;

use App\Entity\CommentMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentMark[]    findAll()
 * @method CommentMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentMarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentMark::class);
    }

    // /**
    //  * @return CommentMark[] Returns an array of CommentMark objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentMark
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
