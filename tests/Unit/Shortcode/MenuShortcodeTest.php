<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Tests\Unit\Shortcode;

use NumberNine\Shortcode\MenuShortcode;
use NumberNine\Tests\ShortcodeTestCase;

final class MenuShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = MenuShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        self::assertEquals([
            'menuItems' => [],
            'style' => null,
        ], $parameters);
    }

    public function testShortcodeWithRandomArguments(): void
    {
        $parameters = $this->processParameters(['random' => 'nonexistent']);

        self::assertEquals([
            'menuItems' => [],
            'style' => null,
        ], $parameters);
    }

    public function testShortcodeWithNonexistentMenu(): void
    {
        $parameters = $this->processParameters(['id' => 123]);

        self::assertEquals([
            'menuItems' => [],
            'style' => null,
        ], $parameters);
    }
}
