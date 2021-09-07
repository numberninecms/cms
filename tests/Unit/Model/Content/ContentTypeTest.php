<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Unit\Model\Content;

use NumberNine\Entity\Post;
use NumberNine\Model\Content\ContentType;
use NumberNine\Model\Content\ContentTypeLabels;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * @internal
 * @coversNothing
 */
final class ContentTypeTest extends TestCase
{
    public function testEmptyArguments(): void
    {
        $this->expectException(MissingOptionsException::class);
        new ContentType([]);
    }

    public function testMissingEntityClassName(): void
    {
        $this->expectException(MissingOptionsException::class);
        new ContentType(['name' => 'post']);
    }

    public function testMissingName(): void
    {
        $this->expectException(MissingOptionsException::class);
        new ContentType(['entity_class_name' => Post::class]);
    }

    public function testMinimumRequiredArguments(): void
    {
        static::assertInstanceOf(
            ContentType::class,
            new ContentType(
                [
                    'name' => 'post',
                    'entity_class_name' => Post::class,
                ]
            )
        );
    }

    public function testSnakeName(): void
    {
        $contentType = new ContentType(
            [
                'name' => '% media file @#$',
                'entity_class_name' => Post::class,
            ]
        );

        static::assertSame('media_file', $contentType->getName());
    }

    public function testNonNullLabels(): void
    {
        $contentType = new ContentType(
            [
                'name' => 'media_file',
                'entity_class_name' => Post::class,
            ]
        );

        static::assertInstanceOf(ContentTypeLabels::class, $contentType->getLabels());
    }
}
