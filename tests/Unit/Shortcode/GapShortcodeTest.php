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

use NumberNine\Shortcode\GapShortcode;
use NumberNine\Tests\ShortcodeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

final class GapShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = GapShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        self::assertEquals([
            'height' => 30,
        ], $parameters);
    }

    public function testValidHeight(): void
    {
        $parameters = $this->processParameters(['height' => 123]);
        self::assertEquals(123, $parameters['height']);
    }

    public function testHeightAsString(): void
    {
        $parameters = $this->processParameters(['height' => '123']);
        self::assertEquals(123, $parameters['height']);
    }

    public function testInvalidHeight(): void
    {
        $parameters = $this->processParameters(['height' => 'invalid']);
        self::assertEquals(30, $parameters['height']);
    }
}
