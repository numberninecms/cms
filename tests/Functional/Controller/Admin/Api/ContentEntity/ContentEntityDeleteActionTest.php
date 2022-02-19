<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional\Controller\Admin\Api\ContentEntity;

use NumberNine\Bundle\Test\UserAwareTestCase;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Security\Capabilities;
use NumberNine\Tests\CreateEntitiesHelperTrait;

/**
 * @internal
 * @covers \NumberNine\Controller\Admin\Api\ContentEntity\ContentEntityDeleteAction
 */
final class ContentEntityDeleteActionTest extends UserAwareTestCase
{
    use CreateEntitiesHelperTrait;

    public function testUnauthenticatedUserCantAccessUrl(): void
    {
        $author = $this->createUser('Author');
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PUBLISH);
        $this->client->request(
            'DELETE',
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseRedirects($this->urlGenerator->generate('numbernine_login'));
    }

    public function testUnauthorizedUserCantDeletePost(): void
    {
        $author = $this->createUser('Author');
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PUBLISH);
        $this->loginThenNavigateToUrl(
            [Capabilities::ACCESS_ADMIN],
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
            'DELETE',
        );

        self::assertResponseRedirects('/');
    }

    public function testAuthorCanDeleteOwnPost(): void
    {
        $author = $this->createUser([Capabilities::ACCESS_ADMIN, Capabilities::DELETE_POSTS]);
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_DRAFT);
        $this->loginThenNavigateToUrl(
            $author,
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
            'DELETE',
        );

        self::assertResponseIsSuccessful();
        static::assertEquals('[]', $this->client->getResponse()->getContent());
    }

    public function testAuthorCantDeleteOthersPost(): void
    {
        $author = $this->createUser();
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PUBLISH);
        $this->loginThenNavigateToUrl(
            [Capabilities::ACCESS_ADMIN, Capabilities::DELETE_POSTS],
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
            'DELETE',
        );

        self::assertResponseRedirects('/');
    }

    public function testEditorCanDeleteOthersPost(): void
    {
        $author = $this->createUser();
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_DRAFT);
        $this->loginThenNavigateToUrl(
            [Capabilities::ACCESS_ADMIN, Capabilities::DELETE_POSTS, Capabilities::DELETE_OTHERS_POSTS],
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
            'DELETE',
        );

        self::assertResponseIsSuccessful();
        static::assertEquals('[]', $this->client->getResponse()->getContent());
    }

    public function testAuthorCantDeleteOwnPublishedPost(): void
    {
        $author = $this->createUser([Capabilities::ACCESS_ADMIN, Capabilities::DELETE_POSTS]);
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PUBLISH);
        $this->loginThenNavigateToUrl(
            $author,
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
            'DELETE',
        );

        self::assertResponseRedirects('/');
    }

    public function testEditorCanDeletePublishedPost(): void
    {
        $author = $this->createUser();
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PUBLISH);
        $this->loginThenNavigateToUrl(
            [
                Capabilities::ACCESS_ADMIN,
                Capabilities::DELETE_POSTS,
                Capabilities::DELETE_OTHERS_POSTS,
                Capabilities::DELETE_PUBLISHED_POSTS,
            ],
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
            'DELETE',
        );

        self::assertResponseIsSuccessful();
        static::assertEquals('[]', $this->client->getResponse()->getContent());
    }

    public function testAuthorCantDeleteOwnPrivatePost(): void
    {
        $author = $this->createUser([Capabilities::ACCESS_ADMIN, Capabilities::DELETE_POSTS]);
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PRIVATE);
        $this->loginThenNavigateToUrl(
            $author,
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
            'DELETE',
        );

        self::assertResponseRedirects('/');
    }

    public function testEditorCanDeletePrivatePost(): void
    {
        $author = $this->createUser();
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PRIVATE);
        $this->loginThenNavigateToUrl(
            [
                Capabilities::ACCESS_ADMIN,
                Capabilities::DELETE_POSTS,
                Capabilities::DELETE_OTHERS_POSTS,
                Capabilities::DELETE_PRIVATE_POSTS,
            ],
            $this->urlGenerator->generate('numbernine_admin_contententity_delete_item', [
                'type' => 'post',
                'id' => $post->getId(),
            ]),
            'DELETE',
        );

        self::assertResponseIsSuccessful();
        static::assertEquals('[]', $this->client->getResponse()->getContent());
    }
}
