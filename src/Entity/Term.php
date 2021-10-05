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
use NumberNine\Repository\TermRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TermRepository::class)]
#[UniqueEntity(['slug'])]
#[NormalizationContext(groups: ['term_get'])]
class Term
{
    use CustomFieldsTrait;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     */
    #[ORM\Column(type: 'string', unique: true)]
    #[Groups(['term_get'])]
    protected ?string $slug = null;

    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column(type: 'integer')]
    #[Groups(['term_get', 'content_entity_get', 'content_entity_get_full'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['term_get', 'content_entity_get', 'content_entity_get_full'])]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Taxonomy::class, inversedBy: 'terms')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['term_get', 'content_entity_get'])]
    private ?Taxonomy $taxonomy = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['term_get'])]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[Groups(['term_get'])]
    private ?Term $parent = null;

    /**
     * @var Collection|Term[]
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    /**
     * @var Collection|ContentEntityTerm[]
     */
    #[ORM\OneToMany(targetEntity: ContentEntityTerm::class, mappedBy: 'term', orphanRemoval: true)]
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
            static function (ContentEntityTerm $item): ?ContentEntity {
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
