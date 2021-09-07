<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Attribute;

use Attribute;
use NumberNine\Model\PageBuilder\Position;

#[Attribute(Attribute::TARGET_CLASS)]
final class Shortcode implements DescriptorAnnotation
{
    public function __construct(
        public string $name,
        public string $label = '',
        public string $description = '',
        public bool $container = false,
        public string $icon = 'dashboard',
        /*
         * Can contain any of these values: top, bottom, left, right
         * @var Position[]
         */
        public array $siblingsPosition = [Position::TOP, Position::BOTTOM],
        /*
         * Values can be any other shortcodes to restrict its content.
         * If no value is specified, any shortcode can be added.
         * @var string[]
         */
        public array $siblingsShortcodes = [],
    ) {
    }
}
