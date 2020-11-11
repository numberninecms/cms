<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Shortcode;

use NumberNine\Annotation\Shortcode\Exclude;

trait ResponsiveShortcodeTrait
{
    protected array $visibility = ['lg', 'md', 'sm'];

    public function getVisibility(): array
    {
        return $this->visibility;
    }

    public function setVisibility(array $visibility = []): void
    {
        $this->visibility = $visibility;
    }

    /**
     * @Exclude
     */
    public function getVisibleViewSizes(): array
    {
        return $this->visibility;
    }

    /**
     * @Exclude
     */
    public function getHiddenViewSizes(): array
    {
        return array_diff(['lg', 'md', 'sm'], $this->visibility);
    }
}
