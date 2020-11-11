<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\ColumnShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(
 *     name="col",
 *     label="Column",
 *     editable=true,
 *     container=true,
 *     icon="view_column",
 *     siblingsPosition={"left", "right"},
 *     siblingsShortcodes={"col"}
 * )
 */
final class ColumnShortcode extends AbstractShortcode
{
    /**
     * @Control\Slider(label="Width", min=0.0, max=12.0, step=1.0)
     */
    public string $span = '12';

    public function getFlexSpan(): string
    {
        if (preg_match('@1/(\d+?)@', $this->span, $matches)) {
            return 'col-span-' . (int)(12 / $matches[1]);
        }

        if (is_numeric($this->span) && (int)$this->span <= 12) {
            return 'col-span-' . $this->span;
        }

        return 'col-span-12';
    }
}
