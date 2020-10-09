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

use NumberNine\Theme\ThemeToolbox;

trait ToolboxTrait
{
    protected ThemeToolbox $toolbox;

    /**
     * @param ThemeToolbox $toolbox
     * @return self
     */
    final public function setToolbox(ThemeToolbox $toolbox): self
    {
        $this->toolbox = $toolbox;
        return $this;
    }
}
