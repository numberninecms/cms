<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional\Controller\Admin\Ui\ContentEntity;

use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Security\Capabilities;
use NumberNine\Tests\CreateEntitiesHelperTrait;
use NumberNine\Tests\UserAwareTestCase;

/**
 * @internal
 * @covers \NumberNine\Controller\Admin\Ui\ContentEntity\ContentEntityEditAction
 */
final class ContentEntityEditActionTest extends UserAwareTestCase
{
    use CreateEntitiesHelperTrait;

    public function testAuthorCantEditOwnPostIfPublished(): void
    {
        $user = $this->createUser([Capabilities::ACCESS_ADMIN, Capabilities::EDIT_POSTS]);
        $post = $this->createPost($user, PublishingStatusInterface::STATUS_PUBLISH);

        $this->loginThenNavigateToAdminUrl(
            $user,
            $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
                'type' => 'posts',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseRedirects('/');
    }

    public function testAuthorCantEditOwnPostIfPrivate(): void
    {
        $user = $this->createUser([Capabilities::ACCESS_ADMIN, Capabilities::EDIT_POSTS]);
        $post = $this->createPost($user, PublishingStatusInterface::STATUS_PRIVATE);

        $this->loginThenNavigateToAdminUrl(
            $user,
            $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
                'type' => 'posts',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseRedirects('/');
    }

    public function testAuthorCanEditOwnPostIfDraft(): void
    {
        $user = $this->createUser([Capabilities::ACCESS_ADMIN, Capabilities::EDIT_POSTS]);
        $post = $this->createPost($user, PublishingStatusInterface::STATUS_DRAFT);

        $this->loginThenNavigateToAdminUrl(
            $user,
            $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
                'type' => 'posts',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseIsSuccessful();
    }

    public function testAuthorCantEditOtherAuthorPostIfDraft(): void
    {
        $user = $this->createUser([Capabilities::ACCESS_ADMIN, Capabilities::EDIT_POSTS]);
        $author = $this->createUser('Author');
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_DRAFT);

        $this->loginThenNavigateToAdminUrl(
            $user,
            $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
                'type' => 'posts',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseRedirects('/');
    }

    public function testEditorCanEditOtherAuthorPostIfDraft(): void
    {
        $editor = $this->createUser([
            Capabilities::ACCESS_ADMIN,
            Capabilities::EDIT_POSTS,
            Capabilities::EDIT_OTHERS_POSTS,
        ]);

        $author = $this->createUser('Author');
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_DRAFT);

        $this->loginThenNavigateToAdminUrl(
            $editor,
            $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
                'type' => 'posts',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseIsSuccessful();
    }

    public function testEditorCanEditOtherAuthorPostIfPublished(): void
    {
        $editor = $this->createUser([
            Capabilities::ACCESS_ADMIN,
            Capabilities::EDIT_POSTS,
            Capabilities::EDIT_OTHERS_POSTS,
        ]);

        $author = $this->createUser('Author');
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PUBLISH);

        $this->loginThenNavigateToAdminUrl(
            $editor,
            $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
                'type' => 'posts',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseIsSuccessful();
    }

    public function testEditorWithoutCapabilityCantEditOtherAuthorPostIfPrivate(): void
    {
        $editor = $this->createUser([
            Capabilities::ACCESS_ADMIN,
            Capabilities::EDIT_POSTS,
            Capabilities::EDIT_OTHERS_POSTS,
        ]);

        $author = $this->createUser('Author');
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PRIVATE);

        $this->loginThenNavigateToAdminUrl(
            $editor,
            $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
                'type' => 'posts',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseRedirects('/');
    }

    public function testEditorCanEditOtherAuthorPostIfPrivate(): void
    {
        $editor = $this->createUser([
            Capabilities::ACCESS_ADMIN,
            Capabilities::EDIT_POSTS,
            Capabilities::EDIT_OTHERS_POSTS,
            Capabilities::EDIT_PRIVATE_POSTS,
        ]);

        $author = $this->createUser('Author');
        $post = $this->createPost($author, PublishingStatusInterface::STATUS_PRIVATE);

        $this->loginThenNavigateToAdminUrl(
            $editor,
            $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
                'type' => 'posts',
                'id' => $post->getId(),
            ]),
        );

        self::assertResponseIsSuccessful();
    }
}
