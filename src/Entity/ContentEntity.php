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

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use NumberNine\Model\Content\CommentStatusInterface;
use NumberNine\Model\Content\Features\AuthorTrait;
use NumberNine\Model\Content\Features\CommentsTrait;
use NumberNine\Model\Content\Features\CustomFieldsTrait;
use NumberNine\Model\Content\Features\CustomTypeTrait;
use NumberNine\Model\Content\Features\EditorTrait;
use NumberNine\Model\Content\Features\SeoTrait;
use NumberNine\Model\Content\Features\WebAccessTrait;
use NumberNine\Model\Content\PublishingStatusInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\ContentEntityRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="content_type", type="string")
 * @ORM\Table(name="contententity")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @Gedmo\Loggable
 */
class ContentEntity implements PublishingStatusInterface, CommentStatusInterface
{
    use WebAccessTrait;
    use EditorTrait;
    use AuthorTrait;
    use SeoTrait;
    use CustomTypeTrait;
    use CustomFieldsTrait;
    use CommentsTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     * @ORM\Column(type="string", unique=true)
     * @Groups({"content_entity_get", "content_entity_get_full"})
     * @Gedmo\Versioned
     */
    protected ?string $slug = null;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"content_entity_get", "content_entity_get_full"})
     */
    protected $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\User", inversedBy="contentEntities")
     * @Groups({"author_get", "content_entity_get_full"})
     */
    protected ?User $author = null;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"content_entity_get", "content_entity_get_full", "menu_get"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\ContentEntity")
     */
    private ?ContentEntity $parent = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $menuOrder = null;

    /**
     * @Gedmo\Timestampable(on="change", field="status", value="publish")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $publishedAt = null;

    /**
     * @ORM\OneToMany(
     *     targetEntity="NumberNine\Entity\ContentEntityTerm",
     *     mappedBy="contentEntity",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private Collection $contentEntityTerms;

    /**
     * @ORM\OneToMany(targetEntity="NumberNine\Entity\ContentEntityRelationship", mappedBy="child", cascade={"persist"})
     */
    private Collection $children;

    /**
     * @ORM\OneToMany(
     *     targetEntity="NumberNine\Entity\ContentEntityRelationship",
     *     mappedBy="parent",
     *     cascade={"persist"}
     * )
     */
    private Collection $parents;

    public function __construct()
    {
        $this->contentEntityTerms = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->setCommentStatus(self::COMMENT_STATUS_OPEN);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMenuOrder(): ?int
    {
        return $this->menuOrder;
    }

    public function setMenuOrder(int $menuOrder): self
    {
        $this->menuOrder = $menuOrder;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get terms sorted by their position.
     *
     * @return Term[]
     * @Groups({"content_entity_get", "content_entity_get_full"})
     */
    public function getTerms(Taxonomy|string|null $taxonomy = null): array
    {
        $sorted = $this->contentEntityTerms->toArray();
        usort(
            $sorted,
            static function (ContentEntityTerm $a, ContentEntityTerm $b): int {
                return $a->getPosition() <=> $b->getPosition();
            }
        );

        /** @var Term[] $terms */
        $terms = array_map(static function (ContentEntityTerm $item): ?Term {
            return $item->getTerm();
        }, $sorted);

        if (!$taxonomy) {
            return $terms;
        }

        return array_filter(
            $terms,
            static function (Term $term) use ($taxonomy): bool {
                if ($taxonomy instanceof Taxonomy) {
                    return ($tax = $term->getTaxonomy()) && $tax->getId() === $taxonomy->getId();
                }

                return ($tax = $term->getTaxonomy()) && $tax->getName() === $taxonomy;
            }
        );
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getContentEntityTerms(): Collection
    {
        return $this->contentEntityTerms;
    }

    public function addContentEntityTerm(ContentEntityTerm $contentEntityTerm): self
    {
        if (!$this->contentEntityTerms->contains($contentEntityTerm)) {
            $this->contentEntityTerms[] = $contentEntityTerm;
            $contentEntityTerm->setContentEntity($this);
        }

        return $this;
    }

    public function removeContentEntityTerm(ContentEntityTerm $contentEntityTerm): self
    {
        if ($this->contentEntityTerms->contains($contentEntityTerm)) {
            $this->contentEntityTerms->removeElement($contentEntityTerm);
            // set the owning side to null (unless already changed)
            if ($contentEntityTerm->getContentEntity() === $this) {
                $contentEntityTerm->setContentEntity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContentEntityRelationship[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(ContentEntityRelationship $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(ContentEntityRelationship $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getChildrenByRelationshipName(string $name): Collection
    {
        return $this->children->filter(fn (ContentEntityRelationship $r): bool => $r->getName() === $name);
    }

    /**
     * @return Collection|ContentEntityRelationship[]
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(ContentEntityRelationship $parent): self
    {
        if (!$this->parents->contains($parent)) {
            $this->parents[] = $parent;
            $parent->setChild($this);
        }

        return $this;
    }

    public function removeParent(ContentEntityRelationship $parent): self
    {
        if ($this->parents->contains($parent)) {
            $this->parents->removeElement($parent);
            // set the owning side to null (unless already changed)
            if ($parent->getChild() === $this) {
                $parent->setChild(null);
            }
        }

        return $this;
    }

    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
