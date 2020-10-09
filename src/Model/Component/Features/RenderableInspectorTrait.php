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

use NumberNine\Content\RenderableInspector;

trait RenderableInspectorTrait
{
    protected RenderableInspector $renderableInspector;

    final public function setRenderableInspector(RenderableInspector $renderableInspector): self
    {
        $this->renderableInspector = $renderableInspector;
        return $this;
    }
}
