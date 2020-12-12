<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\SectionShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(
 *     name="section",
 *     label="Section",
 *     container=true,
 *     editable=true,
 *     icon="web",
 *     siblingsPosition={"top", "bottom"}
 * )
 */
final class SectionShortcode extends AbstractShortcode
{
}
