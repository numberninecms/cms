<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Content\Features;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CustomTypeTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $customType;

    public function getCustomType(): ?string
    {
        return $this->customType;
    }

    public function setCustomType(string $customType): self
    {
        $this->customType = $customType;

        return $this;
    }

    /**
     * @Groups({"content_entity_get", "content_entity_get_full"})
     */
    public function getType(): string
    {
        return strtolower($this->customType ?? basename(static::class));
    }
}
