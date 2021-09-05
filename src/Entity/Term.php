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
use Gedmo\Mapping\Annotation as Gedmo;
use NumberNine\Attribute\NormalizationContext;
use NumberNine\Model\Content\Features\CustomFieldsTrait;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\TermRepository")
 */
#[NormalizationContext(groups: ['term_get'])]
class Term
{
    use CustomFieldsTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"term_get", "content_entity_get", "content_entity_get_full"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"term_get", "content_entity_get", "content_entity_get_full"})
     */
    private ?string $name = null;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(type="string", unique=false)
     * @Groups("term_get")
     */
    protected ?string $slug = null;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\Taxonomy", inversedBy="terms")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"content_entity_get", "term_get"})
     */
    private ?Taxonomy $taxonomy = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("term_get")
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\Term", inversedBy="children")
     * @Groups("term_get")
     */
    private ?Term $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="NumberNine\Entity\Term", mappedBy="parent")
     * @var Collection|Term[]
     */
    private Collection $children;

    /**
     * @ORM\OneToMany(targetEntity="NumberNine\Entity\ContentEntityTerm", mappedBy="term", orphanRemoval=true)
     * @var Collection|ContentEntityTerm[]
     */
    private Collection $contentEntityTerms;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->contentEntityTerms = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
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

    public function getTaxonomy(): ?Taxonomy
    {
        return $this->taxonomy;
    }

    public function setTaxonomy(?Taxonomy $taxonomy): self
    {
        $this->taxonomy = $taxonomy;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ContentEntity[]
     */
    public function getContentEntities(): Collection
    {
        // @phpstan-ignore-next-line
        return $this->contentEntityTerms->map(
            static function (ContentEntityTerm $item): ?\NumberNine\Entity\ContentEntity {
                return $item->getContentEntity();
            }
        );
    }

    public function addContentEntityTerm(ContentEntityTerm $contentEntityTerm): self
    {
        if (!$this->contentEntityTerms->contains($contentEntityTerm)) {
            $this->contentEntityTerms[] = $contentEntityTerm;
            $contentEntityTerm->setTerm($this);
        }

        return $this;
    }

    public function removeContentEntityTerm(ContentEntityTerm $contentEntityTerm): self
    {
        if ($this->contentEntityTerms->contains($contentEntityTerm)) {
            $this->contentEntityTerms->removeElement($contentEntityTerm);
            // set the owning side to null (unless already changed)
            if ($contentEntityTerm->getTerm() === $this) {
                $contentEntityTerm->setTerm(null);
            }
        }

        return $this;
    }
}
