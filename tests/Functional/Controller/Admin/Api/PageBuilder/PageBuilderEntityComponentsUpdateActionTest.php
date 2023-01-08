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

namespace NumberNine\Tests\Functional\Controller\Admin\Api\PageBuilder;

use NumberNine\Entity\Post;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Security\Capabilities;
use NumberNine\Test\UserAwareTestCase;

/**
 * @internal
 * @coversNothing
 */
final class PageBuilderEntityComponentsUpdateActionTest extends UserAwareTestCase
{
    public function testNotLoggedInUserCantAccessUrl(): void
    {
        $post = $this->getPost();

        $this->client->request('POST', $this->urlGenerator->generate(
            'numbernine_admin_pagebuilder_post_entity_components',
            ['id' => $post->getId()],
        ));
        self::assertResponseRedirects($this->urlGenerator->generate('numbernine_login'));
    }

    public function testNonAllowedUserCantAccessUrl(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::ACCESS_ADMIN]);
        $post = $this->getPost();

        $this->client->request('POST', $this->urlGenerator->generate(
            'numbernine_admin_pagebuilder_post_entity_components',
            ['id' => $post->getId()],
        ));
        self::assertResponseRedirects($this->urlGenerator->generate('numbernine_login'));
    }

    public function testUpdatePostComponents(): void
    {
        $this->setCapabilitiesThenLogin([
            Capabilities::ACCESS_ADMIN,
            Capabilities::EDIT_POSTS,
            Capabilities::EDIT_OTHERS_POSTS,
        ]);
        $post = $this->getPost();

        $this->client->request(
            'POST',
            $this->urlGenerator->generate(
                'numbernine_admin_pagebuilder_post_entity_components',
                ['id' => $post->getId()],
            ),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            file_get_contents(
                __DIR__ . '/../../../../../Fixtures/Controller/Admin/Api/PageBuilder/PageBuilderEntityComponentsUpdateActionTest/content.json'
            ),
        );

        self::assertResponseIsSuccessful();

        $post = $this->entityManager->getRepository(Post::class)->find($post->getId());
        static::assertSame(<<<'CONTENT'
        Add a new component to this page...
        [button text="View more..." case="normal" color="primary" style="default" size="normal"]
        CONTENT, $post->getContent());
    }

    private function getPost(): Post
    {
        $author = $this->createUser('Contributor');

        $post = (new Post())
            ->setTitle('My blog post')
            ->setCustomType('post')
            ->setAuthor($author)
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('2013/12/18'))
            ->setPublishedAt(new \DateTime('2015/05/13'))
        ;

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }
}
