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
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\TaxonomyRepository")
 */
class Taxonomy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"taxonomy_get"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"taxonomy_get", "content_entity_get", "term_get"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="json")
     * @Groups({"taxonomy_get"})
     */
    private array $contentTypes = [];

    /**
     * @ORM\OneToMany(targetEntity="NumberNine\Entity\Term", mappedBy="taxonomy", orphanRemoval=true)
     * @var Collection|Term[]
     */
    private Collection $terms;

    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContentTypes(): ?array
    {
        return $this->contentTypes;
    }

    public function setContentTypes(array $contentTypes): self
    {
        $this->contentTypes = $contentTypes;

        return $this;
    }

    /**
     * @return Collection|Term[]
     */
    public function getTerms(): Collection
    {
        return $this->terms;
    }

    public function addTerm(Term $term): self
    {
        if (!$this->terms->contains($term)) {
            $this->terms[] = $term;
            $term->setTaxonomy($this);
        }

        return $this;
    }

    public function removeTerm(Term $term): self
    {
        if ($this->terms->contains($term)) {
            $this->terms->removeElement($term);
            // set the owning side to null (unless already changed)
            if ($term->getTaxonomy() === $this) {
                $term->setTaxonomy(null);
            }
        }

        return $this;
    }
}
