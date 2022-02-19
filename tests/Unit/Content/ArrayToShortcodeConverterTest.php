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

namespace NumberNine\Tests\Unit\Content;

use NumberNine\Bundle\Test\DotEnvAwareWebTestCase;
use NumberNine\Content\ArrayToShortcodeConverter;

/**
 * @internal
 * @covers \NumberNine\Content\ArrayToShortcodeConverter
 */
final class ArrayToShortcodeConverterTest extends DotEnvAwareWebTestCase
{
    private ArrayToShortcodeConverter $arrayToShortcodeConverter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->arrayToShortcodeConverter = static::getContainer()->get(ArrayToShortcodeConverter::class);
    }

    public function testConvertSingleComponentToShortcode(): void
    {
        $shortcode = $this->arrayToShortcodeConverter->convertMany([
            [
                'name' => 'my_shortcode',
                'parameters' => [
                    'content' => 'Some content',
                    'margin' => '0px',
                    'padding' => '0',
                    'color' => 'light',
                    'empty' => '',
                    'null_value' => null,
                    'integer_zero' => 0,
                    'camelCase' => 'ok',
                    'snake_case' => 'ok',
                ],
                'children' => [],
            ],
        ]);

        static::assertSame(
            '[my_shortcode margin="0px" padding="0" color="light" integer_zero="0" ' .
            'camelCase="ok" snake_case="ok"]Some content[/my_shortcode]',
            $shortcode
        );
    }
}
