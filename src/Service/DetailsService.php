<?php
/**
 * Details service.
 */

namespace App\Service;

use App\Entity\Details;
use App\Repository\DetailsRepository;

/**
 * Class DetailsService.
 */
class DetailsService
{
    /**
     * Details repository.
     *
     * @var \App\Repository\DetailsRepository
     */
    private $detailsRepository;

    /**
     * DetailsService constructor.
     *
     * @param \App\Repository\DetailsRepository $detailsRepository Details repository
     */
    public function __construct(DetailsRepository $detailsRepository)
    {
        $this->detailsRepository = $detailsRepository;
    }

    /**
     * Save details.
     *
     * @param \App\Entity\Details $details Details entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Details $details): void
    {
        $this->detailsRepository->save($details);
    }
}
