<?php
/**
 * Post Mark entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CommentMark.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PostMarkRepository")
 * @ORM\Table(name="posts_marks")
 */
class PostMark
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
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="postMark", orphanRemoval=true)
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="postMark")
     */
    private $user;

    /**
     * PostMark constructor.
     */
    public function __construct()
    {
        $this->post = new ArrayCollection();
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
     * @return Collection|Post[]
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function addPost(Post $post): self
    {
        if (!$this->post->contains($post)) {
            $this->post[] = $post;
            $post->setPostMark($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function removePost(Post $post): self
    {
        if ($this->post->contains($post)) {
            $this->post->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getPostMark() === $this) {
                $post->setPostMark(null);
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
            $user->setPostMark($this);
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
            if ($user->getPostMark() === $this) {
                $user->setPostMark(null);
            }
        }

        return $this;
    }
}
