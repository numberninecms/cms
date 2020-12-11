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
}
