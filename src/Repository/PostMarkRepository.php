<?php

namespace App\Repository;

use App\Entity\PostMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostMark[]    findAll()
 * @method PostMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostMarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostMark::class);
    }

    // /**
    //  * @return PostMark[] Returns an array of PostMark objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostMark
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
