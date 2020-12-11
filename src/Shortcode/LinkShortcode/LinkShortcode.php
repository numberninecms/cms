<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\LinkShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(name="link", label="Link", editable=true, container=true, icon="link")
 */
final class LinkShortcode extends AbstractShortcode
{
}
