<?php
/**
 * Comment Mark entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CommentMark.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CommentMarkRepository")
 * @ORM\Table(name="comments_marks")
 */
class CommentMark
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Mark.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="integer"
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="1",
     *     max="5",
     * )
     */
    private $mark;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comment", inversedBy="commentMarks", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentMarks", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getMark(): ?int
    {
        return $this->mark;
    }

    /**
     * @param int $mark
     *
     * @return $this
     */
    public function setMark(int $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * @return Comment|null
     */
    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    /**
     * @param Comment|null $comment
     *
     * @return $this
     */
    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
