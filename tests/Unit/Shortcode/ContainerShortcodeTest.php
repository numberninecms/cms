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
use NumberNine\Shortcode\ContainerShortcode;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @internal
 * @coversNothing
 */
final class ContainerShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = ContainerShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        static::assertSame([
            'align' => 'start',
            'justify' => 'start',
            'styles' => ' style="margin:0 auto"',
            'content' => '',
        ], $parameters);
    }

    public function testShortcodeWithContent(): void
    {
        $parameters = $this->processParameters(['content' => 'Sample content']);
        static::assertSame('Sample content', $parameters['content']);
    }

    public function testOrientation(): void
    {
        $this->processParameters(['orientation' => 'horizontal']);
        $this->processParameters(['orientation' => 'vertical']);

        $this->expectException(InvalidOptionsException::class);
        $this->processParameters(['orientation' => 'invalid_value']);
    }

    public function testJustify(): void
    {
        $this->processParameters(['justify' => 'start']);
        $this->processParameters(['justify' => 'center']);
        $this->processParameters(['justify' => 'end']);
        $this->processParameters(['justify' => 'between']);
        $this->processParameters(['justify' => 'around']);

        $this->expectException(InvalidOptionsException::class);
        $this->processParameters(['justify' => 'invalid_value']);
    }

    public function testAlign(): void
    {
        $this->processParameters(['align' => 'start']);
        $this->processParameters(['align' => 'center']);
        $this->processParameters(['align' => 'end']);
        $this->processParameters(['align' => 'stretch']);
        $this->processParameters(['align' => 'baseline']);

        self::expectException(InvalidOptionsException::class);
        $this->processParameters(['align' => 'invalid_value']);
    }

    public function testMarginWithSingleValue(): void
    {
        $parameters = $this->processParameters(['margin' => '10px']);
        static::assertSame(' style="margin:10px"', $parameters['styles']);
    }

    public function testMarginWithTwoValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto']);
        static::assertSame(' style="margin:10px auto"', $parameters['styles']);
    }

    public function testMarginWithThreeValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem']);
        static::assertSame(' style="margin:10px auto 13rem"', $parameters['styles']);
    }

    public function testMarginWithFourValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem 0']);
        static::assertSame(' style="margin:10px auto 13rem 0"', $parameters['styles']);
    }

    public function testMarginWithFiveValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem 0 25px']);
        static::assertSame(' style="margin:0 auto"', $parameters['styles']);
    }

    public function testMarginWithInvalidValue(): void
    {
        $parameters = $this->processParameters(['margin' => 'invalid']);
        static::assertSame(' style="margin:0 auto"', $parameters['styles']);
    }

    public function testPaddingWithSingleValue(): void
    {
        $parameters = $this->processParameters(['padding' => '10px']);
        static::assertSame(' style="margin:0 auto;padding:10px"', $parameters['styles']);
    }

    public function testPaddingWithTwoValues(): void
    {
        $parameters = $this->processParameters(['padding' => '10px auto']);
        static::assertSame(' style="margin:0 auto;padding:10px auto"', $parameters['styles']);
    }

    public function testPaddingWithThreeValues(): void
    {
        $parameters = $this->processParameters(['padding' => '10px auto 13rem']);
        static::assertSame(' style="margin:0 auto;padding:10px auto 13rem"', $parameters['styles']);
    }

    public function testPaddingWithFourValues(): void
    {
        $parameters = $this->processParameters(['padding' => '10px auto 13rem 0']);
        static::assertSame(' style="margin:0 auto;padding:10px auto 13rem 0"', $parameters['styles']);
    }

    public function testPaddingWithFiveValues(): void
    {
        $parameters = $this->processParameters(['padding' => '10px auto 13rem 0 25px']);
        static::assertSame(' style="margin:0 auto"', $parameters['styles']);
    }

    public function testPaddingWithInvalidValue(): void
    {
        $parameters = $this->processParameters(['padding' => 'invalid']);
        static::assertSame(' style="margin:0 auto"', $parameters['styles']);
    }
}
