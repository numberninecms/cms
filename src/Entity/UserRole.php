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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\UserRoleRepository")
 * @ORM\Table(name="userrole")
 * @UniqueEntity("name")
 */
class UserRole
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_get", "role_get"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"user_get", "role_get"})
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="json")
     * @Groups("role_get")
     * @var string[]
     */
    private array $capabilities = [];

    /**
     * @ORM\ManyToMany(targetEntity="NumberNine\Entity\User", mappedBy="userRoles")
     * @var Collection|User[]
     */
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    public function setCapabilities(array $capabilities): self
    {
        $this->capabilities = $capabilities;
        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addUserRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeUserRole($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
