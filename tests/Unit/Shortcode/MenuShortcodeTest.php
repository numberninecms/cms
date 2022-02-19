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

use NumberNine\Bundle\Test\ShortcodeTestCase;
use NumberNine\Shortcode\MenuShortcode;

/**
 * @internal
 * @coversNothing
 */
final class MenuShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = MenuShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        static::assertSame([
            'menuItems' => [],
            'style' => 'main',
        ], $parameters);
    }

    public function testShortcodeWithRandomArguments(): void
    {
        $parameters = $this->processParameters(['random' => 'nonexistent']);

        static::assertSame([
            'menuItems' => [],
            'style' => 'main',
        ], $parameters);
    }

    public function testShortcodeWithNonexistentMenu(): void
    {
        $parameters = $this->processParameters(['id' => 123]);

        static::assertSame([
            'menuItems' => [],
            'style' => 'main',
        ], $parameters);
    }
}
