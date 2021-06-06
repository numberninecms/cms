<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\ContentEntityRelationshipRepository")
 * @ORM\Table(name="contententityrelationship")
 */
class ContentEntityRelationship
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\ContentEntity", inversedBy="parents")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ContentEntity $parent = null;

    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\ContentEntity", inversedBy="children")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ContentEntity $child = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?ContentEntity
    {
        return $this->parent;
    }

    public function setParent(?ContentEntity $source): self
    {
        $this->parent = $source;

        return $this;
    }

    public function getChild(): ?ContentEntity
    {
        return $this->child;
    }

    public function setChild(?ContentEntity $target): self
    {
        $this->child = $target;

        return $this;
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
}
