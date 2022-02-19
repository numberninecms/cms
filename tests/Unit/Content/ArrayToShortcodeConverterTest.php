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

use NumberNine\Content\ArrayToShortcodeConverter;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @covers \NumberNine\Content\ArrayToShortcodeConverter
 */
final class ArrayToShortcodeConverterTest extends WebTestCase
{
    private KernelBrowser $client;
    private ArrayToShortcodeConverter $arrayToShortcodeConverter;

    protected function setUp(): void
    {
        $this->client = static::createClient();
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
