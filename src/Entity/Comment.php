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

use ArrayAccess;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use NumberNine\Model\Content\Features\AuthorTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\CommentRepository")
 */
class Comment
{
    use AuthorTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $content;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $guestAuthorName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $guestAuthorEmail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $guestAuthorUrl;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\Comment", inversedBy="children")
     */
    private ?Comment $parent;

    /**
     * @ORM\OneToMany(targetEntity="NumberNine\Entity\Comment", mappedBy="parent", fetch="EAGER", orphanRemoval=true)
     */
    private Collection $children;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\ContentEntity", inversedBy="comments")
     */
    private ?ContentEntity $contentEntity;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\User", inversedBy="comments")
     * @Groups("author_get")
     */
    protected ?User $author = null;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getGuestAuthorName(): ?string
    {
        return $this->guestAuthorName;
    }

    public function setGuestAuthorName(?string $guestAuthorName): self
    {
        $this->guestAuthorName = $guestAuthorName;
        return $this;
    }

    public function getGuestAuthorEmail(): ?string
    {
        return $this->guestAuthorEmail;
    }

    public function setGuestAuthorEmail(?string $guestAuthorEmail): self
    {
        $this->guestAuthorEmail = $guestAuthorEmail;
        return $this;
    }

    public function getGuestAuthorUrl(): ?string
    {
        return $this->guestAuthorUrl;
    }

    public function setGuestAuthorUrl(?string $guestAuthorUrl): self
    {
        $this->guestAuthorUrl = $guestAuthorUrl;
        return $this;
    }

    public function getParent(): ?Comment
    {
        return $this->parent;
    }

    public function setParent(?Comment $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getChildren(): ArrayAccess
    {
        return $this->children;
    }

    public function setChildren(Collection $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getContentEntity(): ?ContentEntity
    {
        return $this->contentEntity;
    }

    public function setContentEntity(?ContentEntity $contentEntity): self
    {
        $this->contentEntity = $contentEntity;
        return $this;
    }

    public function getAuthorName(): string
    {
        return (string)($this->getAuthor() instanceof User
            ? $this->getAuthor()->getDisplayName()
            : $this->getGuestAuthorName());
    }

    public function getAuthorEmail(): string
    {
        return (string)($this->getAuthor() instanceof User
            ? $this->getAuthor()->getEmail()
            : $this->getGuestAuthorEmail());
    }
}
