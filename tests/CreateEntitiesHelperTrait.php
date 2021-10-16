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
use NumberNine\Entity\Post;
use NumberNine\Entity\User;

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
}
