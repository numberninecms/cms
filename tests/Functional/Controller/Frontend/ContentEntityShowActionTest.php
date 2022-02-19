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

namespace NumberNine\Tests\Functional\Controller\Frontend;

use NumberNine\Bundle\Test\UserAwareTestCase;
use NumberNine\Entity\Post;
use NumberNine\Entity\User;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Security\Capabilities;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \NumberNine\Controller\Frontend\Content\ContentEntityShowAction
 */
final class ContentEntityShowActionTest extends UserAwareTestCase
{
    public function testAccessingNonExistentUrlThrows404NotFound(): void
    {
        $this->client->request('GET', '/thisurldoesnotexist');

        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAccessingPrivatePostThrows404NotFound(): void
    {
        $this->createPrivatePost('2021/04/15');
        $this->client->request('GET', '/2021/04/15/this-page-is-private');

        static::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAuthorCanAccessHisPrivatePost(): void
    {
        $user = $this->loginAs('Contributor');
        $this->createPrivatePost('2021/04/15', $user);
        $this->client->request('GET', '/2021/04/15/this-page-is-private');

        static::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSelectorTextSame('h1', 'Private: This page is private');
    }

    public function testAllowedUsersCanAccessOtherUserPrivatePost(): void
    {
        $this->createPrivatePost('2021/04/15');
        $this->setCapabilitiesThenLogin([Capabilities::READ_PRIVATE_POSTS]);
        $this->client->request('GET', '/2021/04/15/this-page-is-private');

        static::assertResponseStatusCodeSame(Response::HTTP_OK);
        static::assertSelectorTextSame('h1', 'Private: This page is private');
    }

    private function createPrivatePost(string $date, ?User $user = null): void
    {
        if (!$user) {
            $user = $this->createUser('Contributor');
        }

        $post = (new Post())
            ->setCustomType('post')
            ->setAuthor($user)
            ->setTitle('This page is private')
            ->setStatus(PublishingStatusInterface::STATUS_PRIVATE)
            ->setCreatedAt(new \DateTime($date))
        ;

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}
