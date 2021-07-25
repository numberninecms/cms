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

use NumberNine\Shortcode\TitleShortcode;
use NumberNine\Tests\ShortcodeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

final class TitleShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = TitleShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        self::assertEquals([
            'tag' => 'h2',
            'color' => null,
            'style' => 'center',
            'text' => 'Title',
            'size' => 'normal',
            'margin' => '30px 0',
        ], $parameters);
    }

    public function testContentOverridesDefaultText(): void
    {
        $parameters = $this->processParameters(['content' => 'Sample content']);
        self::assertEquals('Sample content', $parameters['text']);
    }

    public function testCustomTextOverridesContent(): void
    {
        $parameters = $this->processParameters([
            'content' => 'Sample content',
            'text' => 'This should override content'
        ]);

        self::assertEquals('This should override content', $parameters['text']);
    }

    public function testTag(): void
    {
        $this->processParameters(['tag' => 'h1']);
        $this->processParameters(['tag' => 'h2']);
        $this->processParameters(['tag' => 'h3']);
        $this->processParameters(['tag' => 'h4']);
        $this->processParameters(['tag' => 'h5']);
        $this->processParameters(['tag' => 'h6']);
        $this->processParameters(['tag' => 'div']);

        $this->expectException(InvalidOptionsException::class);
        $this->processParameters(['tag' => 'invalid']);
    }

    public function testSize(): void
    {
        $this->processParameters(['size' => 'xsmall']);
        $this->processParameters(['size' => 'small']);
        $this->processParameters(['size' => 'normal']);
        $this->processParameters(['size' => 'large']);
        $this->processParameters(['size' => 'xlarge']);
        $this->processParameters(['size' => 'xxlarge']);

        $this->expectException(InvalidOptionsException::class);
        $this->processParameters(['size' => 'invalid']);
    }

    public function testStyle(): void
    {
        $this->processParameters(['style' => 'center']);
        $this->processParameters(['style' => 'left']);

        $this->expectException(InvalidOptionsException::class);
        $this->processParameters(['style' => 'invalid']);
    }

    public function testMarginWithSingleValue(): void
    {
        $parameters = $this->processParameters(['margin' => '10px']);
        self::assertEquals('10px', $parameters['margin']);
    }

    public function testMarginWithTwoValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto']);
        self::assertEquals('10px auto', $parameters['margin']);
    }

    public function testMarginWithThreeValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem']);
        self::assertEquals('10px auto 13rem', $parameters['margin']);
    }

    public function testMarginWithFourValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem 0']);
        self::assertEquals('10px auto 13rem 0', $parameters['margin']);
    }

    public function testMarginWithFiveValues(): void
    {
        $parameters = $this->processParameters(['margin' => '10px auto 13rem 0 25px']);
        self::assertEquals('30px 0', $parameters['margin']);
    }

    public function testMarginWithInvalidValue(): void
    {
        $parameters = $this->processParameters(['margin' => 'invalid']);
        self::assertEquals('30px 0', $parameters['margin']);
    }
}
