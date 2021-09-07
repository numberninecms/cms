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

use NumberNine\Shortcode\ButtonShortcode;
use NumberNine\Tests\ShortcodeTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ButtonShortcodeTest extends ShortcodeTestCase
{
    protected const SHORTCODE = ButtonShortcode::class;

    public function testShortcodeWithoutArguments(): void
    {
        $parameters = $this->processParameters([]);

        static::assertSame([
            'text' => 'View more...',
            'case' => 'normal',
            'color' => 'primary',
            'style' => 'default',
            'size' => 'normal',
            'expand' => false,
            'link' => '',
            'custom_class' => '',
        ], $parameters);
    }

    public function testContentOverridesDefaultText(): void
    {
        $parameters = $this->processParameters(['content' => 'Sample content']);
        static::assertSame('Sample content', $parameters['text']);
    }

    public function testCustomTextOverridesContent(): void
    {
        $parameters = $this->processParameters([
            'content' => 'Sample content',
            'text' => 'This should override content',
        ]);

        static::assertSame('This should override content', $parameters['text']);
    }

    public function testShortcodeWithRandomArguments(): void
    {
        $parameters = $this->processParameters(['random' => 'nonexistent']);

        static::assertSame([
            'text' => 'View more...',
            'case' => 'normal',
            'color' => 'primary',
            'style' => 'default',
            'size' => 'normal',
            'expand' => false,
            'link' => '',
            'custom_class' => '',
        ], $parameters);
    }
}
