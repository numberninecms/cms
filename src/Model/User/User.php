<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\User;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use NumberNine\Entity\UserRole;
use NumberNine\Model\Content\Features\CustomFieldsTrait;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\MappedSuperclass
 */
abstract class User implements UserInterface, Serializable
{
    use CustomFieldsTrait;
    use TimestampableEntity;

    public const DISPLAY_NAME_USERNAME = 'username';
    public const DISPLAY_NAME_FIRST_ONLY = 'first_only';
    public const DISPLAY_NAME_LAST_ONLY = 'last_only';
    public const DISPLAY_NAME_FIRST_LAST = 'first_last';
    public const DISPLAY_NAME_LAST_FIRST = 'last_first';

    protected int $id;

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
     * @var string The hashed password
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

        return array_map(fn(UserRole $userRole) => $userRole->getName(), $userRoles);
    }

    abstract public function getUserRoles(): Collection;

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
}
