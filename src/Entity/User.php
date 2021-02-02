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
use Gedmo\Timestampable\Traits\TimestampableEntity;
use NumberNine\Model\Content\Features\CustomFieldsTrait;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="This username is already taken.")
 * @UniqueEntity(fields={"email"}, message="A user is already registered with this email.")
 */
class User implements UserInterface, Serializable
{
    use CustomFieldsTrait;
    use TimestampableEntity;

    public const DISPLAY_NAME_USERNAME = 'username';
    public const DISPLAY_NAME_FIRST_ONLY = 'first_only';
    public const DISPLAY_NAME_LAST_ONLY = 'last_only';
    public const DISPLAY_NAME_FIRST_LAST = 'first_last';
    public const DISPLAY_NAME_LAST_FIRST = 'last_first';

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

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_get", "author_get"})
     */
    private ?string $username = null;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_get"})
     */
    private ?string $email = null;

    /**
     * @var ?string The hashed password
     * @ORM\Column(type="string")
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user_get"})
     */
    private ?string $firstName = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"user_get"})
     */
    private ?string $lastName = null;

    /**
     * @ORM\Column(type="string")
     * @Groups({"user_get"})
     */
    private ?string $displayNameFormat = self::DISPLAY_NAME_USERNAME;

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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $userRoles = $this->getUserRoles();

        if ($userRoles instanceof Collection) {
            $userRoles = $userRoles->toArray();
        }

        return array_map(fn(UserRole $userRole) => (string)$userRole->getName(), $userRoles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     * return null
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getDisplayNameFormat(): ?string
    {
        return $this->displayNameFormat;
    }

    public function setDisplayNameFormat(?string $displayName): self
    {
        $this->displayNameFormat = $displayName;
        return $this;
    }

    /**
     * @Groups({"user_get", "author_get"})
     * @return string
     */
    public function getDisplayName(): string
    {
        switch ($this->displayNameFormat) {
            case self::DISPLAY_NAME_FIRST_ONLY:
                return (string)$this->getFirstName();
            case self::DISPLAY_NAME_LAST_ONLY:
                return (string)$this->getLastName();
            case self::DISPLAY_NAME_FIRST_LAST:
                return trim(sprintf('%s %s', $this->getFirstName(), $this->getLastName()));
            case self::DISPLAY_NAME_LAST_FIRST:
                return trim(sprintf('%s %s', $this->getLastName(), $this->getFirstName()));
            case self::DISPLAY_NAME_USERNAME:
            default:
                return $this->getUsername();
        }
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
            ]
        );
    }

    /** @param string $serialized
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->username,
            $this->password,
        ] = unserialize($serialized, ['allowed_classes' => []]);
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

    public function __toString(): string
    {
        return $this->getDisplayName();
    }
}
