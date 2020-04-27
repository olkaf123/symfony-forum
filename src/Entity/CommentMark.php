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
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="commentMark", orphanRemoval=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="commentMark", orphanRemoval=true)
     */
    private $user;

    /**
     * CommentMark constructor.
     */
    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

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
     * @return $this
     */
    public function setMark(int $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setCommentMark($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comment->contains($comment)) {
            $this->comment->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCommentMark() === $this) {
                $comment->setCommentMark(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setCommentMark($this);
        }

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCommentMark() === $this) {
                $user->setCommentMark(null);
            }
        }

        return $this;
    }
}
