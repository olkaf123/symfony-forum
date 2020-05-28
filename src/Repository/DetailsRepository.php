<?php

namespace App\Repository;

use App\Entity\Details;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Details|null find($id, $lockMode = null, $lockVersion = null)
 * @method Details|null findOneBy(array $criteria, array $orderBy = null)
 * @method Details[]    findAll()
 * @method Details[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Details::class);
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Details $details Details entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Details $details): void
    {
        $this->_em->persist($details);
        $this->_em->flush($details);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Details $details Details entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Details $details): void
    {
        $this->_em->remove($details);
        $this->_em->flush($details);
    }
}
