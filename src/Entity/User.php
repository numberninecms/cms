<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use NumberNine\Model\User\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="This username is already taken.")
 * @UniqueEntity(fields={"email"}, message="A user is already registered with this email.")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_get", "author_get"})
     */
    protected int $id;

    /**
     * @ORM\ManyToMany(targetEntity="NumberNine\Entity\UserRole", inversedBy="users", fetch="EAGER")
     * @ORM\JoinTable(name="userrole_user")
     * @Groups({"user_get"})
     * @var Collection|UserRole[]
     */
    private Collection $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="NumberNine\Entity\ContentEntity", mappedBy="author")
     * @var Collection|ContentEntity[]
     */
    private Collection $contentEntities;

    /**
     * @ORM\OneToMany(targetEntity="NumberNine\Entity\Comment", mappedBy="author")
     * @var Collection|Comment[]
     */
    private Collection $comments;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->contentEntities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @see UserInterface
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(UserRole $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    public function getContentEntities(): Collection
    {
        return $this->contentEntities;
    }

    public function addContentEntity(ContentEntity $contentEntity): self
    {
        if (!$this->contentEntities->contains($contentEntity)) {
            $this->contentEntities[] = $contentEntity;
            $contentEntity->setAuthor($this);
        }

        return $this;
    }

    public function removeContentEntity(ContentEntity $contentEntity): self
    {
        if ($this->contentEntities->contains($contentEntity)) {
            $this->contentEntities->removeElement($contentEntity);
            if ($contentEntity->getAuthor() === $this) {
                $contentEntity->setAuthor(null);
            }
        }

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getDisplayName();
    }
}
