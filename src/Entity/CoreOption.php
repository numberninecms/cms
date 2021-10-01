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
use NumberNine\Repository\ContentEntityRelationshipRepository;

#[ORM\Entity(repositoryClass: ContentEntityRelationshipRepository::class)]
#[ORM\Table(name: 'coreoption')]
class CoreOption
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $value;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
