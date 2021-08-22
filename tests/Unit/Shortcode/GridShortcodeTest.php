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

final class GridShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = GridShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        self::assertEquals([
            'content' => '',
            'columns_count' => 3,
        ], $parameters);
    }

    public function testValidColumnsCount(): void
    {
        $parameters = $this->processParameters(['columnsCount' => 12]);
        self::assertEquals(12, $parameters['columns_count']);
    }

    public function testColumnsCountAsString(): void
    {
        $parameters = $this->processParameters(['columnsCount' => '12']);
        self::assertEquals(12, $parameters['columns_count']);
    }

    public function testColumnsCountOutOfRangeUpper(): void
    {
        $parameters = $this->processParameters(['columnsCount' => 13]);
        self::assertEquals(12, $parameters['columns_count']);
    }

    public function testColumnsCountOutOfRangeLower(): void
    {
        $parameters = $this->processParameters(['columnsCount' => 0]);
        self::assertEquals(1, $parameters['columns_count']);
    }

    public function testInvalidColumnsCount(): void
    {
        $parameters = $this->processParameters(['columnsCount' => 'invalid']);
        self::assertEquals(3, $parameters['columns_count']);

        $parameters = $this->processParameters(['columnsCount' => 6.75]);
        self::assertEquals(6, $parameters['columns_count']);
    }
}
