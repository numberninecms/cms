<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Unit\Content;

use NumberNine\Command\ThemeAwareCommandInterface;
use NumberNine\Content\ShortcodeProcessor;
use NumberNine\Tests\DotEnvAwareWebTestCase;
use Symfony\Component\Serializer\SerializerInterface;

use function NumberNine\Common\Util\ArrayUtil\unset_recursive;

final class ShortcodeProcessorTest extends DotEnvAwareWebTestCase implements ThemeAwareCommandInterface
{
    private const SAMPLE_SHORTCODE = '
        [container justify="center" align="center" margin="10px auto 10px auto"]
            [link href="http://numbernine/" title="NumberNine - The most user friendly CMS based on Symfony"]
                NumberNine
            [/link]
        [/container]
    ';

    private ?ShortcodeProcessor $shortcodeProcessor;
    private ?SerializerInterface $serializer;

    public function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->shortcodeProcessor = self::$container->get(ShortcodeProcessor::class);
        $this->serializer = self::$container->get('serializer');
    }

    public function testBuildShortcodeTreeCreatesArray(): void
    {
        $tree = $this->shortcodeProcessor->buildShortcodeTree(self::SAMPLE_SHORTCODE, true, false, true);

        self::assertIsArray($tree);
    }

    public function testBuildShortcodeTreeCanBeSerialized(): void
    {
        $serialized = $this->serializer->serialize(
            $this->shortcodeProcessor->buildShortcodeTree(self::SAMPLE_SHORTCODE, true, false, true),
            'json',
        );

        self::assertIsArray(json_decode($serialized, true));
    }

    public function testBuildShortcodeTreeOutputIsCorrect(): void
    {
        $tree = $this->shortcodeProcessor->buildShortcodeTree(self::SAMPLE_SHORTCODE, true, false, true);

        unset_recursive($tree, 'id');
        unset_recursive($tree, 'content');
        unset_recursive($tree, 'position');

        self::assertEquals(
            [
                0 => [
                    'type' => 'NumberNine\\Shortcode\\ContainerShortcode',
                    'name' => 'container',
                    'parameters' => [
                        'justify' => 'center',
                        'align' => 'center',
                        'margin' => '10px auto 10px auto',
                        'padding' => '',
                        'orientation' => 'horizontal'
                    ],
                    'responsive' => [
                    ],
                    'computed' => [
                    ],
                    'editable' => true,
                    'container' => true,
                    'label' => 'Container',
                    'siblingsPosition' => [
                        0 => 'top',
                        1 => 'bottom',
                    ],
                    'siblingsShortcodes' => [
                    ],
                    'icon' => 'mdi-table-row',
                    'children' => [
                        0 => [
                            'type' => 'NumberNine\\Shortcode\\LinkShortcode',
                            'name' => 'link',
                            'parameters' => [
                                'href' => 'http://numbernine/',
                                'title' => 'NumberNine - The most user friendly CMS based on Symfony',
                            ],
                            'responsive' => [
                            ],
                            'computed' => [
                            ],
                            'editable' => true,
                            'container' => true,
                            'label' => 'Link',
                            'siblingsPosition' => [
                                0 => 'top',
                                1 => 'bottom',
                            ],
                            'siblingsShortcodes' => [
                            ],
                            'icon' => 'mdi-link',
                            'children' => [
                                0 => [
                                    'type' => 'NumberNine\\Shortcode\\TextShortcode',
                                    'name' => 'text',
                                    'parameters' => [
                                    ],
                                    'responsive' => [
                                    ],
                                    'computed' => [
                                    ],
                                    'editable' => true,
                                    'container' => false,
                                    'leaf' => true,
                                    'label' => 'Text',
                                    'siblingsPosition' => [
                                        0 => 'top',
                                        1 => 'bottom',
                                    ],
                                    'siblingsShortcodes' => [
                                    ],
                                    'icon' => 'mdi-format-text-variant',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $tree
        );
    }
}
