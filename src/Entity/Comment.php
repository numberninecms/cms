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
use Gedmo\Mapping\Annotation as Gedmo;
use NumberNine\Model\Content\Features\AuthorTrait;
use NumberNine\Model\Content\Features\SoftDeleteableTrait;
use NumberNine\Model\Content\Features\TimestampableTrait;
use NumberNine\Model\Content\SoftDeletableEntity;
use NumberNine\Repository\CommentRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment implements SoftDeletableEntity
{
    use AuthorTrait;
    use TimestampableTrait;
    use SoftDeleteableTrait;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[Groups('author_get')]
    protected ?User $author = null;

    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $guestAuthorName;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $guestAuthorEmail;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $guestAuthorUrl;

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?Comment $parent;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent', fetch: 'EAGER', orphanRemoval: true)]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: ContentEntity::class, inversedBy: 'comments')]
    private ?ContentEntity $contentEntity;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
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
        return (string) ($this->getAuthor() instanceof User
            ? $this->getAuthor()->getDisplayName()
            : $this->getGuestAuthorName());
    }

    public function getAuthorEmail(): string
    {
        return (string) ($this->getAuthor() instanceof User
            ? $this->getAuthor()->getEmail()
            : $this->getGuestAuthorEmail());
    }
}
