<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\Table(name="comments")
 */
class Comment
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
     * Comment.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="text"
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3"
     * )
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments", fetch="EXTRA_LAZY")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentMark", mappedBy="comment", orphanRemoval=true)
     */
    private $commentMarks;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->commentMarks = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return $this
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Post|null
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post|null $post
     *
     * @return $this
     */
    public function setPost(?Post $post): self
    {
        $this->post = $post;

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

    /**
     * @return Collection|CommentMark[]
     */
    public function getCommentMarks(): Collection
    {
        return $this->commentMarks;
    }

    /**
     * @param CommentMark $commentMark
     *
     * @return $this
     */
    public function addCommentMark(CommentMark $commentMark): self
    {
        if (!$this->commentMarks->contains($commentMark)) {
            $this->commentMarks[] = $commentMark;
            $commentMark->setComment($this);
        }

        return $this;
    }

    /**
     * @param CommentMark $commentMark
     *
     * @return $this
     */
    public function removeCommentMark(CommentMark $commentMark): self
    {
        if ($this->commentMarks->contains($commentMark)) {
            $this->commentMarks->removeElement($commentMark);
            // set the owning side to null (unless already changed)
            if ($commentMark->getComment() === $this) {
                $commentMark->setComment(null);
            }
        }

        return $this;
    }
}
