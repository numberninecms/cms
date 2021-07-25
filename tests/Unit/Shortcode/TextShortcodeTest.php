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

use NumberNine\Shortcode\TextShortcode;
use NumberNine\Tests\ShortcodeTestCase;

final class TextShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = TextShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        self::assertEquals([
            'content' => '',
        ], $parameters);
    }

    public function testShortcodeWithContent(): void
    {
        $parameters = $this->processParameters(['content' => 'Sample content']);

        self::assertEquals([
            'content' => 'Sample content',
        ], $parameters);
    }

    public function testShortcodeWithRandomArguments(): void
    {
        $parameters = $this->processParameters(['random' => 'nonexistent']);

        self::assertEquals([
            'content' => '',
        ], $parameters);
    }
}
