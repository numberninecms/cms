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

namespace NumberNine\Tests\Unit\Twig\Extension;

use NumberNine\Content\ContentService;
use NumberNine\Entity\ContentEntityRelationship;
use NumberNine\Entity\MediaFile;
use NumberNine\Entity\Post;
use NumberNine\Media\MediaFileFactory;
use NumberNine\Test\UserAwareTestCase;
use NumberNine\Twig\Extension\MediaRuntime;

/**
 * @internal
 * @coversNothing
 */
final class MediaRuntimeTest extends UserAwareTestCase
{
    private MediaRuntime $runtime;
    private MediaFileFactory $mediaFileFactory;
    private MediaFile $mediaFile;
    private ContentService $contentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->runtime = static::getContainer()->get(MediaRuntime::class);
        $this->mediaFileFactory = static::getContainer()->get(MediaFileFactory::class);
        $this->contentService = static::getContainer()->get(ContentService::class);

        $this->mediaFile = $this->mediaFileFactory
            ->createMediaFileFromFilename(__DIR__ . '/../../../../assets/images/NumberNine512_slogan.png', null)
        ;
    }

    public function testGetMaxUploadSize(): void
    {
        static::assertIsInt($this->runtime->getMaxUploadSize());
    }

    public function testGetFeaturedImage(): void
    {
        $post = (new Post())->setTitle('My blog post');

        $data = (new ContentEntityRelationship())
            ->setParent($post)
            ->setChild($this->mediaFile)
            ->setName('featured_image')
        ;

        $this->entityManager->persist($post);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        static::assertMatchesRegularExpression(
            sprintf(
                '@^<img src="/uploads/%s/NumberNine512-slogan(-\d+)?.png.512x140.png" ' .
                'width="512" height="140" alt="%s">$@',
                date('Y/m'),
                $post->getTitle(),
            ),
            $this->runtime->getFeaturedImage($post),
        );
    }

    public function testPostSupportsFeaturedImage(): void
    {
        static::assertTrue($this->runtime->supportsFeaturedImage($this->contentService->getContentType('post')));
    }

    public function testMediaFileDoesNotSupportsFeaturedImage(): void
    {
        static::assertFalse($this->runtime->supportsFeaturedImage($this->contentService->getContentType('media_file')));
    }

    public function testGetImageUrl(): void
    {
        static::assertMatchesRegularExpression(
            sprintf('@^/uploads/%s/NumberNine512-slogan(-\d+)?.png$@', date('Y/m')),
            $this->runtime->getImageUrl($this->mediaFile),
        );
    }

    public function testGetImageThumbnailUrl(): void
    {
        static::assertMatchesRegularExpression(
            sprintf('@^/uploads/%s/NumberNine512-slogan(-\d+)?.png.150x140.png$@', date('Y/m')),
            $this->runtime->getImageUrl($this->mediaFile, 'thumbnail'),
        );
    }

    public function testGetImage(): void
    {
        static::assertMatchesRegularExpression(
            sprintf(
                '@^<img src="/uploads/%s/NumberNine512-slogan(-\d+)?.png.512x140.png" ' .
                'width="512" height="140" alt="%s">$@',
                date('Y/m'),
                $this->mediaFile->getTitle(),
            ),
            $this->runtime->getImage($this->mediaFile),
        );
    }
}
