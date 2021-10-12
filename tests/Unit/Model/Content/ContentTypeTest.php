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
use NumberNine\Security\Capabilities;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * @internal
 * @covers \NumberNine\Model\Content\ContentType
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
            new ContentType([
                'name' => 'post',
                'entity_class_name' => Post::class,
            ]),
        );
    }

    public function testSnakeName(): void
    {
        $contentType = new ContentType([
            'name' => '% media file @#$',
            'entity_class_name' => Post::class,
        ]);

        static::assertSame('media_file', $contentType->getName());
    }

    public function testNonNullLabels(): void
    {
        $contentType = new ContentType([
            'name' => 'media_file',
            'entity_class_name' => Post::class,
        ]);

        static::assertInstanceOf(ContentTypeLabels::class, $contentType->getLabels());
    }

    public function testCapabilities(): void
    {
        $contentType = new ContentType([
            'name' => 'media_file',
            'entity_class_name' => Post::class,
        ]);

        static::assertEquals([
            'read_posts' => 'read_media_files',
            'read_private_posts' => 'read_private_media_files',
            'edit_posts' => 'edit_media_files',
            'edit_private_posts' => 'edit_private_media_files',
            'edit_others_posts' => 'edit_others_media_files',
            'edit_published_posts' => 'edit_published_media_files',
            'publish_posts' => 'publish_media_files',
            'delete_posts' => 'delete_media_files',
            'delete_private_posts' => 'delete_private_media_files',
            'delete_others_posts' => 'delete_others_media_files',
            'delete_published_posts' => 'delete_published_media_files',
        ], $contentType->getCapabilities());
    }

    public function testExtraCapabilities(): void
    {
        $contentType = new ContentType([
            'name' => 'media_file',
            'entity_class_name' => Post::class,
            'capabilities' => ['extra_capability'],
        ]);

        static::assertEquals([
            'read_posts' => 'read_media_files',
            'read_private_posts' => 'read_private_media_files',
            'edit_posts' => 'edit_media_files',
            'edit_private_posts' => 'edit_private_media_files',
            'edit_others_posts' => 'edit_others_media_files',
            'edit_published_posts' => 'edit_published_media_files',
            'publish_posts' => 'publish_media_files',
            'delete_posts' => 'delete_media_files',
            'delete_private_posts' => 'delete_private_media_files',
            'delete_others_posts' => 'delete_others_media_files',
            'delete_published_posts' => 'delete_published_media_files',
            'extra_capability' => 'extra_capability',
        ], $contentType->getCapabilities());
    }

    public function testMappedCapability(): void
    {
        $contentType = new ContentType([
            'name' => 'media_file',
            'entity_class_name' => Post::class,
        ]);

        static::assertEquals('edit_media_files', $contentType->getMappedCapability(Capabilities::EDIT_POSTS));
    }

    public function testMappedCapabilities(): void
    {
        $contentType = new ContentType([
            'name' => 'media_file',
            'entity_class_name' => Post::class,
        ]);

        static::assertEquals(
            ['edit_media_files', 'delete_private_media_files'],
            $contentType->getMappedCapabilities([Capabilities::EDIT_POSTS, Capabilities::DELETE_PRIVATE_POSTS]),
        );
    }
}
