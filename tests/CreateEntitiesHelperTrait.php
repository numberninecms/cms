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

namespace NumberNine\Tests;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\Comment;
use NumberNine\Entity\Post;
use NumberNine\Entity\User;
use NumberNine\Model\Content\CommentStatusInterface;
use NumberNine\Model\Content\PublishingStatusInterface;

/**
 * @property EntityManagerInterface $entityManager
 */
trait CreateEntitiesHelperTrait
{
    private function createPost(User $user, string $status): Post
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setAuthor($user)
            ->setTitle('Sample post')
            ->setStatus($status)
        ;

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    private function createPostComments(User $user, string $status, int $count = 3): Post
    {
        $post = $this->createPost($user, PublishingStatusInterface::STATUS_PUBLISH);

        for ($i = 0; $i < $count; ++$i) {
            $comment = (new Comment())
                ->setContent('Some content')
                ->setStatus($status)
                ->setContentEntity($post)
                ->setAuthor($user)
            ;

            $post->addComment($comment);
            $this->entityManager->persist($comment);
        }

        $this->entityManager->flush();

        return $post;
    }

    /**
     * @return Comment[]
     */
    private function createOrphanComments(
        int $count = 10,
        string $status = CommentStatusInterface::COMMENT_STATUS_APPROVED
    ): array {
        $comments = [];

        for ($i = 0; $i < $count; ++$i) {
            $comment = (new Comment())
                ->setContent('Some content')
                ->setStatus($status)
                ->setGuestAuthorName('John')
                ->setGuestAuthorEmail('john@numberninecms.com')
            ;

            $this->entityManager->persist($comment);

            $comments[] = $comment;
        }

        $this->entityManager->flush();

        return $comments;
    }
}
