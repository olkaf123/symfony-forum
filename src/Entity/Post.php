<?php
/**
 * Post entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="posts")
 */
class Post
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
     * Topic.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $topic;

    /**
     * Post.
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
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PostMark", inversedBy="post")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postMark;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
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
    public function getTopic(): ?string
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     * @return $this
     */
    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPost(): ?string
    {
        return $this->post;
    }

    /**
     * @param string $post
     * @return $this
     */
    public function setPost(string $post): self
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
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return PostMark|null
     */
    public function getPostMark(): ?PostMark
    {
        return $this->postMark;
    }

    /**
     * @param PostMark|null $postMark
     * @return $this
     */
    public function setPostMark(?PostMark $postMark): self
    {
        $this->postMark = $postMark;

        return $this;
    }
}
