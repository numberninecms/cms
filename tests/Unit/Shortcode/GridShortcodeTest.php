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

use NumberNine\Shortcode\GridShortcode;
use NumberNine\Tests\ShortcodeTestCase;

/**
 * @internal
 * @coversNothing
 */
final class GridShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = GridShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        static::assertSame([
            'content' => '',
            'columns_count' => 3,
        ], $parameters);
    }

    public function testValidColumnsCount(): void
    {
        $parameters = $this->processParameters(['columnsCount' => 12]);
        static::assertSame(12, $parameters['columns_count']);
    }

    public function testColumnsCountAsString(): void
    {
        $parameters = $this->processParameters(['columnsCount' => '12']);
        static::assertSame(12, $parameters['columns_count']);
    }

    public function testColumnsCountOutOfRangeUpper(): void
    {
        $parameters = $this->processParameters(['columnsCount' => 13]);
        static::assertSame(12, $parameters['columns_count']);
    }

    public function testColumnsCountOutOfRangeLower(): void
    {
        $parameters = $this->processParameters(['columnsCount' => 0]);
        static::assertSame(1, $parameters['columns_count']);
    }

    public function testInvalidColumnsCount(): void
    {
        $parameters = $this->processParameters(['columnsCount' => 'invalid']);
        static::assertSame(3, $parameters['columns_count']);

        $parameters = $this->processParameters(['columnsCount' => 6.75]);
        static::assertSame(6, $parameters['columns_count']);
    }
}
