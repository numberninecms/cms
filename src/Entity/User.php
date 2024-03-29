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
use NumberNine\Model\Content\Features\CustomFieldsTrait;
use NumberNine\Model\Content\Features\TimestampableTrait;
use NumberNine\Repository\UserRepository;
use Serializable;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'This username is already taken.')]
#[UniqueEntity(fields: ['email'], message: 'A user is already registered with this email.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Serializable, Stringable
{
    use CustomFieldsTrait;
    use TimestampableTrait;

    public const DISPLAY_NAME_USERNAME = 'username';
    public const DISPLAY_NAME_FIRST_ONLY = 'first_only';
    public const DISPLAY_NAME_LAST_ONLY = 'last_only';
    public const DISPLAY_NAME_FIRST_LAST = 'first_last';
    public const DISPLAY_NAME_LAST_FIRST = 'last_first';

    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column(type: 'integer')]
    #[Groups(['user_get', 'author_get'])]
    protected int $id;

    /**
     * @var Collection|UserRole[]
     */
    #[ORM\ManyToMany(targetEntity: UserRole::class, inversedBy: 'users', fetch: 'EAGER')]
    #[ORM\JoinTable(name: 'userrole_user')]
    #[Groups(['user_get'])]
    private Collection $userRoles;

    /**
     * @var Collection|ContentEntity[]
     */
    #[ORM\OneToMany(targetEntity: ContentEntity::class, mappedBy: 'author')]
    private Collection $contentEntities;

    /**
     * @var Collection|Comment[]
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'author')]
    private Collection $comments;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user_get', 'author_get'])]
    private ?string $username = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user_get'])]
    private ?string $email = null;

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['user_get'])]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['user_get'])]
    private ?string $lastName = null;

    #[ORM\Column(type: 'string')]
    #[Groups(['user_get'])]
    private ?string $displayNameFormat = self::DISPLAY_NAME_USERNAME;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->contentEntities = new ArrayCollection();
    }

    public function __serialize(): array
    {
        return [$this->id, $this->username, $this->password];
    }

    public function __unserialize(array $data): void
    {
        [
            $this->id,
            $this->username,
            $this->password,
        ] = $data;
    }

    public function __toString(): string
    {
        return $this->getDisplayName();
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
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function setEmail(?string $email): self
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

        return array_map(static fn (UserRole $userRole): string => (string) $userRole->getName(), $userRoles);
    }

    public function hasRole(string $role): bool
    {
        return \in_array($role, $this->getRoles(), true);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
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

    #[Groups(['user_get', 'author_get'])]
    public function getDisplayName(): string
    {
        return match ($this->displayNameFormat) {
            self::DISPLAY_NAME_FIRST_ONLY => (string) $this->getFirstName(),
            self::DISPLAY_NAME_LAST_ONLY => (string) $this->getLastName(),
            self::DISPLAY_NAME_FIRST_LAST => trim(sprintf('%s %s', $this->getFirstName(), $this->getLastName())),
            self::DISPLAY_NAME_LAST_FIRST => trim(sprintf('%s %s', $this->getLastName(), $this->getFirstName())),
            default => $this->getUsername(),
        };
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
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
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
}
