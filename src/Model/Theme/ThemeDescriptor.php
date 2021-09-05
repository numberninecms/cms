<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Theme;

use InvalidArgumentException;
use NumberNine\Attribute\DescriptorAnnotation;
use NumberNine\Attribute\Theme;
use NumberNine\Model\General\Descriptor;

final class ThemeDescriptor implements Descriptor
{
    private string $name;
    private string $slug;
    private string $mainEntry;
    private array $areas;

    /**
     * @return never
     */
    public function __construct(DescriptorAnnotation $metadata)
    {
        if (!$metadata instanceof Theme) {
            throw new InvalidArgumentException();
        }

        $this->name = $metadata->name;
        $this->mainEntry = $metadata->mainEntry;
        $this->areas = $metadata->areas;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getMainEntry(): string
    {
        return $this->mainEntry;
    }

    public function getAreas(): array
    {
        return $this->areas;
    }
}
