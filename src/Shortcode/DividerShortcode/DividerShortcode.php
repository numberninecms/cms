<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\DividerShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;

/**
 * @Shortcode(name="divider", label="Divider", editable=true, icon="remove")
 */
final class DividerShortcode extends AbstractShortcode
{
}
