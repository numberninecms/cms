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

namespace NumberNine\Tests\Unit\Twig\Extension;

use NumberNine\Exception\InvalidHexColorValueException;
use NumberNine\Tests\DotEnvAwareWebTestCase;
use NumberNine\Twig\Extension\ColorRuntime;

/**
 * @internal
 * @covers \NumberNine\Twig\Extension\ColorRuntime
 */
final class ColorRuntimeTest extends DotEnvAwareWebTestCase
{
    private ColorRuntime $runtime;

    protected function setUp(): void
    {
        parent::setUp();
        $this->runtime = static::getContainer()->get(ColorRuntime::class);
    }

    public function testHexToRgbWorks(): void
    {
        static::assertSame('70, 166, 233', $this->runtime->hexToRgb('#46a6e9'));
    }

    public function testInvalidHexThrowsException(): void
    {
        $this->expectException(InvalidHexColorValueException::class);
        $this->runtime->hexToRgb('46a6e9');
    }
}
