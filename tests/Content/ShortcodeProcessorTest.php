<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Content;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use NumberNine\Annotation\ExtendedAnnotationReader;
use NumberNine\Content\RenderableInspector;
use NumberNine\Content\ShortcodeProcessor;
use NumberNine\Content\ShortcodeStore;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Repository\PresetRepository;
use NumberNine\Shortcode\FlexRowShortcode\FlexRowShortcode;
use NumberNine\Shortcode\LinkShortcode\LinkShortcode;
use NumberNine\Shortcode\TextShortcode\TextShortcode;
use NumberNine\Theme\PresetFinderInterface;
use NumberNine\Theme\TemplateResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Thunder\Shortcode\Parser\RegularParser;
use Twig\Environment;
use function NumberNine\Util\ArrayUtil\unset_recursive;

class ShortcodeProcessorTest extends TestCase
{
    private const SAMPLE_SHORTCODE = '
        [flex_row justify="center" align="center" margin="10px auto 10px auto"]
            [link href="http://numbernine/" title="NumberNine - The most user friendly CMS based on Symfony"]
                NumberNine
            [/link]
        [/flex_row]
    ';

    private ?ShortcodeProcessor $shortcodeProcessor;
    private ?SerializerInterface $serializer;

    protected function setUp(): void
    {
        $annotationReader = new AnnotationReader(new DocParser());
        $extendedAnnotationReader = new ExtendedAnnotationReader($annotationReader);
        $shortcodeStore = new ShortcodeStore($extendedAnnotationReader);
        $shortcodeParser = new RegularParser();
        $renderableInspector = new RenderableInspector($extendedAnnotationReader);

        $shortcodes = [FlexRowShortcode::class, LinkShortcode::class, TextShortcode::class];

        foreach ($shortcodes as $shortcodeName) {
            /** @var ShortcodeInterface $shortcode */
            $shortcode = new $shortcodeName();

            $shortcode->setTwig($this->createMock(Environment::class));
            $shortcode->setEventDispatcher($this->createMock(EventDispatcherInterface::class));
            $shortcode->setRenderableInspector($renderableInspector);
            $shortcode->setTemplateResolver($this->createMock(TemplateResolverInterface::class));

            $shortcodeStore->addShortcode($shortcode);
        }

        $this->shortcodeProcessor = new ShortcodeProcessor(
            $shortcodeStore,
            $extendedAnnotationReader,
            $this->createMock(PresetRepository::class),
            $this->createMock(PresetFinderInterface::class),
            $shortcodeParser,
            $this->createMock(TagAwareCacheInterface::class)
        );

        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
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
                    'type' => 'NumberNine\\Shortcode\\FlexRowShortcode\\FlexRowShortcode',
                    'name' => 'flex_row',
                    'parameters' => [
                        'justify' => 'center',
                        'align' => 'center',
                        'margin' => '10px auto 10px auto',
                        'padding' => '',
                    ],
                    'responsive' => [
                    ],
                    'computed' => [
                    ],
                    'editable' => true,
                    'container' => true,
                    'label' => 'Flex row',
                    'siblingsPosition' => [
                        0 => 'top',
                        1 => 'bottom',
                    ],
                    'siblingsShortcodes' => [
                    ],
                    'icon' => 'view_stream',
                    'children' => [
                        0 => [
                            'type' => 'NumberNine\\Shortcode\\LinkShortcode\\LinkShortcode',
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
                            'icon' => 'link',
                            'children' => [
                                0 => [
                                    'type' => 'NumberNine\\Shortcode\\TextShortcode\\TextShortcode',
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
                                    'icon' => 'text_fields',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
            , $tree);
    }
}
