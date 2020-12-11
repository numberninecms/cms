<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\LogoShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(
 *     name="logo",
 *     label="Site logo",
 *     description="Displays the site logo with dynamic header tag for better SEO."
 * )
 */
final class LogoShortcode extends AbstractShortcode
{
}
