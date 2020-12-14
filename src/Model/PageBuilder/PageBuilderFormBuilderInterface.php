<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\PageBuilder;

interface PageBuilderFormBuilderInterface
{
    public function add(string $child, string $type = null, array $options = []): self;

    /**
     * @return array Returns the children
     */
    public function all(): array;
}
