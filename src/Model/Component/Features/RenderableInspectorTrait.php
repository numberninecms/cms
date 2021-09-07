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

use NumberNine\Content\RenderableInspectorInterface;

trait RenderableInspectorTrait
{
    protected RenderableInspectorInterface $renderableInspector;

    final public function setRenderableInspector(RenderableInspectorInterface $renderableInspector): self
    {
        $this->renderableInspector = $renderableInspector;

        return $this;
    }
}
