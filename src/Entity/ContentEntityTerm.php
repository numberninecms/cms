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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\ContentEntityTermRepository")
 * @ORM\Table(name="contententity_term", uniqueConstraints={@ORM\UniqueConstraint(columns={"content_entity_id", "term_id"})})
 * @UniqueEntity(fields={"contentEntity", "term"}, errorPath="term", message="This term is already in use on that content entity.")
 */
class ContentEntityTerm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\ContentEntity", inversedBy="contentEntityTerms")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ContentEntity $contentEntity;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\Term", inversedBy="contentEntityTerms")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"content_entity_get", "content_entity_get_full"})
     */
    private ?Term $term;

    /**
     * @ORM\Column(type="integer")
     */
    private int $position = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTerm(): ?Term
    {
        return $this->term;
    }

    public function setTerm(?Term $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
