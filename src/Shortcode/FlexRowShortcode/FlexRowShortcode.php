<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\FlexRowShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(
 *     name="flex_row",
 *     label="Flex row",
 *     editable=true,
 *     container=true,
 *     icon="view_stream",
 *     siblingsPosition={"top", "bottom"}
 * )
 */
final class FlexRowShortcode extends AbstractShortcode
{
}
