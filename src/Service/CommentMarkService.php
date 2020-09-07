<?php
/**
 * Comment Mark service.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\CommentMark;
use App\Entity\User;
use App\Repository\CommentMarkRepository;

/**
 * Class CommentMarkService.
 */
class CommentMarkService
{
    /**
     * CommentMark repository.
     *
     * @var \App\Repository\CommentMarkRepository
     */
    private $commentMarkRepository;

    /**
     * CommentMarkService constructor.
     *
     * @param \App\Repository\CommentMarkRepository $commentMarkRepository CommentMark repository
     */
    public function __construct(CommentMarkRepository $commentMarkRepository)
    {
        $this->commentMarkRepository = $commentMarkRepository;
    }

    /**
     * Save commentMark.
     *
     * @param \App\Entity\CommentMark $commentMark CommentMark entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(CommentMark $commentMark): void
    {
        $this->commentMarkRepository->save($commentMark);
    }

    /**
     * Already voted.
     *
     * @param Comment $comment comment
     * @param User    $user    user
     *
     * @return bool
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function alreadyVoted(Comment $comment, User $user)
    {
        return $this->commentMarkRepository->alreadyVoted($comment, $user);
    }
}
