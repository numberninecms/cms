<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\DataFixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use NumberNine\Entity\Comment;
use NumberNine\Entity\Post;
use NumberNine\Entity\User;

final class CommentFixtures extends BaseFixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            Comment::class,
            FixtureSettings::POSTS_COUNT * 3,
            function (Comment $comment, $i) {
                $comment
                    ->setContent($this->faker->text)
                    ->setContentEntity($this->getReference(Post::class . '_post_' . (int)floor($i / 3)))
                    ->setAuthor($this->getRandomReference(User::class));

                if ($i % 3 === 2 && random_int(0, 2) === 0) {
                    $comment->setParent($this->getReference(Comment::class . '_' . ($i - 1)));
                }
            }
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, PostFixtures::class, PageFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['numbernine_core'];
    }
}
