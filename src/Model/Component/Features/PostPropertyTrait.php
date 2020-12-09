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

trait PostPropertyTrait
{
    protected ?Post $post = null;

    public function setPost(?Post $post): void
    {
        $this->post = $post;
    }
}
