<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component\Features;

use NumberNine\Entity\Post;

trait BlockPropertyTrait
{
    private ?Post $block = null;

    public function getBlock(): ?Post
    {
        return $this->block;
    }

    public function setBlock(?Post $block): void
    {
        $this->block = $block;
    }
}
