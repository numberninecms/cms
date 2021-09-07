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

use NumberNine\Content\PermalinkGenerator;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\Post;
use NumberNine\Exception\ContentTypeNotFoundException;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Tests\DotEnvAwareWebTestCase;

/**
 * @internal
 * @coversNothing
 */
final class PermalinkGeneratorTest extends DotEnvAwareWebTestCase
{
    private PermalinkGenerator $permalinkGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->permalinkGenerator = static::getContainer()->get(PermalinkGenerator::class);
    }

    public function testEmptyContentEntityThrowsException(): void
    {
        $emptyEntity = new ContentEntity();

        $this->expectException(ContentTypeNotFoundException::class);
        $this->permalinkGenerator->generateContentEntityPermalink($emptyEntity);
    }

    public function testNewPostPermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post);
        static::assertSame(sprintf('/%s/new-post', date('Y/m/d')), $permalink);
    }

    public function testPostWithSlugPermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setSlug('my-awesome-post')
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post);
        static::assertSame(sprintf('/%s/my-awesome-post', date('Y/m/d')), $permalink);
    }

    public function testNeverPublishedPostPermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setStatus(PublishingStatusInterface::STATUS_DRAFT)
            ->setSlug('my-awesome-post')
            ->setCreatedAt(new \DateTime('April 21, 2013'))
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post);
        static::assertSame(sprintf('/%s/my-awesome-post', date('2013/04/21')), $permalink);
    }

    public function testPublishedPostPermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setSlug('my-awesome-post')
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('April 21, 2013'))
            ->setPublishedAt(new \DateTime('August 7, 2016'))
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post);
        static::assertSame(sprintf('/%s/my-awesome-post', date('2016/08/07')), $permalink);
    }

    public function testUnpublishedPostAsDraftPermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setSlug('my-awesome-post')
            ->setStatus(PublishingStatusInterface::STATUS_DRAFT)
            ->setCreatedAt(new \DateTime('April 21, 2013'))
            ->setPublishedAt(new \DateTime('August 7, 2016'))
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post);
        static::assertSame(sprintf('/%s/my-awesome-post', date('2016/08/07')), $permalink);
    }

    public function testUnpublishedPostAsPrivatePermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setSlug('my-awesome-post')
            ->setStatus(PublishingStatusInterface::STATUS_PRIVATE)
            ->setCreatedAt(new \DateTime('April 21, 2013'))
            ->setPublishedAt(new \DateTime('August 7, 2016'))
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post);
        static::assertSame(sprintf('/%s/my-awesome-post', date('2016/08/07')), $permalink);
    }

    public function testPublishedPostPaginationPermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setSlug('my-awesome-post')
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('April 21, 2013'))
            ->setPublishedAt(new \DateTime('August 7, 2016'))
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post, 3);
        static::assertSame(sprintf('/%s/my-awesome-post/page/3/', date('2016/08/07')), $permalink);
    }

    public function testPublishedPostAbsoluteUrlPermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setSlug('my-awesome-post')
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('April 21, 2013'))
            ->setPublishedAt(new \DateTime('August 7, 2016'))
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post, 1, true);
        static::assertSame(sprintf('http://localhost/%s/my-awesome-post', date('2016/08/07')), $permalink);
    }

    public function testPublishedPostPaginationAbsoluteUrlPermalink(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setSlug('my-awesome-post')
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('April 21, 2013'))
            ->setPublishedAt(new \DateTime('August 7, 2016'))
        ;

        $permalink = $this->permalinkGenerator->generateContentEntityPermalink($post, 3, true);
        static::assertSame(sprintf('http://localhost/%s/my-awesome-post/page/3/', date('2016/08/07')), $permalink);
    }
}
