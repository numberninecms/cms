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

use NumberNine\Shortcode\ContainerShortcode;
use NumberNine\Tests\ShortcodeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

final class ContainerShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = ContainerShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        self::assertEquals([
            'content' => '',
            'align' => 'start',
            'justify' => 'start',
            'styles' => ' style="margin:0 auto"',
        ], $parameters);
    }

    public function testShortcodeWithContent(): void
    {
        $parameters = $this->processParameters(['content' => 'Sample content']);
        self::assertEquals('Sample content', $parameters['content']);
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
        self::assertEquals(' style="margin:10px"', $parameters['styles']);
    }

    public function testMarginWithTwoValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto']);
        self::assertEquals(' style="margin:10px auto"', $parameters['styles']);
    }

    public function testMarginWithThreeValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem']);
        self::assertEquals(' style="margin:10px auto 13rem"', $parameters['styles']);
    }

    public function testMarginWithFourValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem 0']);
        self::assertEquals(' style="margin:10px auto 13rem 0"', $parameters['styles']);
    }

    public function testMarginWithFiveValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem 0 25px']);
        self::assertEquals(' style="margin:0 auto"', $parameters['styles']);
    }

    public function testMarginWithInvalidValue(): void
    {
        $parameters = $this->processParameters(['margin' => 'invalid']);
        self::assertEquals(' style="margin:0 auto"', $parameters['styles']);
    }

    public function testPaddingWithSingleValue(): void
    {
        $parameters = $this->processParameters(['padding' => '10px']);
        self::assertEquals(' style="margin:0 auto;padding:10px"', $parameters['styles']);
    }

    public function testPaddingWithTwoValues(): void
    {
        $parameters = $this->processParameters(['padding' => '10px auto']);
        self::assertEquals(' style="margin:0 auto;padding:10px auto"', $parameters['styles']);
    }

    public function testPaddingWithThreeValues(): void
    {
        $parameters = $this->processParameters(['padding' => '10px auto 13rem']);
        self::assertEquals(' style="margin:0 auto;padding:10px auto 13rem"', $parameters['styles']);
    }

    public function testPaddingWithFourValues(): void
    {
        $parameters = $this->processParameters(['padding' => '10px auto 13rem 0']);
        self::assertEquals(' style="margin:0 auto;padding:10px auto 13rem 0"', $parameters['styles']);
    }

    public function testPaddingWithFiveValues(): void
    {
        $parameters = $this->processParameters(['padding' => '10px auto 13rem 0 25px']);
        self::assertEquals(' style="margin:0 auto"', $parameters['styles']);
    }

    public function testPaddingWithInvalidValue(): void
    {
        $parameters = $this->processParameters(['padding' => 'invalid']);
        self::assertEquals(' style="margin:0 auto"', $parameters['styles']);
    }
}