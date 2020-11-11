<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Content;

use NumberNine\Model\Component\RenderableInterface;
use NumberNine\Model\Inspector\InspectedRenderable;

interface RenderableInspectorInterface
{
    public function inspect(RenderableInterface $renderable, bool $isSerialization = false): InspectedRenderable;
}
