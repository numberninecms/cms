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

use NumberNine\Content\ShortcodeProcessor;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\SerializerInterface;

final class ShortcodeProcessorTest extends WebTestCase
{
    private const SAMPLE_SHORTCODE = '
        [flex_row justify="center" align="center" margin="10px auto 10px auto"]
            [link href="http://numbernine/" title="NumberNine - The most user friendly CMS based on Symfony"]
                [image fromTitle="NumberNine Logo" maxWidth="581" maxHeight="131"/]
            [/link]
        [/flex_row]
    ';

    private ?ShortcodeProcessor $shortcodeProcessor;
    private ?SerializerInterface $serializer;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->shortcodeProcessor = $container->get(ShortcodeProcessor::class);
        $this->serializer = $container->get(SerializerInterface::class);
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
}
