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

use NumberNine\Shortcode\GridElementShortcode;
use NumberNine\Tests\ShortcodeTestCase;

final class GridElementShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = GridElementShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        self::assertEquals([
            'content' => '',
            'span' => 1,
        ], $parameters);
    }

    public function testValidSpan(): void
    {
        $parameters = $this->processParameters(['span' => 12]);
        self::assertEquals(12, $parameters['span']);
    }

    public function testSpanAsString(): void
    {
        $parameters = $this->processParameters(['span' => '12']);
        self::assertEquals(12, $parameters['span']);
    }

    public function testColumnsCountOutOfRangeUpper(): void
    {
        $parameters = $this->processParameters(['span' => 13]);
        self::assertEquals(12, $parameters['span']);
    }

    public function testColumnsCountOutOfRangeLower(): void
    {
        $parameters = $this->processParameters(['span' => 0]);
        self::assertEquals(1, $parameters['span']);
    }

    public function testInvalidSpan(): void
    {
        $parameters = $this->processParameters(['span' => 'invalid']);
        self::assertEquals(1, $parameters['span']);

        $parameters = $this->processParameters(['span' => 6.75]);
        self::assertEquals(6, $parameters['span']);
    }
}
