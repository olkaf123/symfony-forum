<?php
/**
 * Post Mark service.
 */

namespace App\Service;

use App\Entity\Post;
use App\Entity\PostMark;
use App\Entity\User;
use App\Repository\PostMarkRepository;

/**
 * Class PostMarkService.
 */
class PostMarkService
{
    /**
     * PostMark repository.
     *
     * @var \App\Repository\PostMarkRepository
     */
    private $postMarkRepository;

    /**
     * PostMarkService constructor.
     *
     * @param \App\Repository\PostMarkRepository $postMarkRepository PostMark repository
     */
    public function __construct(PostMarkRepository $postMarkRepository)
    {
        $this->postMarkRepository = $postMarkRepository;
    }

    /**
     * Save postMark.
     *
     * @param \App\Entity\PostMark $postMark PostMark entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(PostMark $postMark): void
    {
        $this->postMarkRepository->save($postMark);
    }

    /**
     * Already voted.
     *
     * @param Post      $post post
     * @param User|null $user user
     *
     * @return bool
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function alreadyVoted(Post $post, User $user = null)
    {
        return $this->postMarkRepository->alreadyVoted($post, $user);
    }

    /**
     * Already voted.
     *
     * @param Post $post post
     *
     * @return int|mixed|string
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countMarkValue(Post $post)
    {
        return $this->postMarkRepository->countMarkValue($post);
    }
}
