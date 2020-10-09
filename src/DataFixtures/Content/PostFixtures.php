<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\DataFixtures\Content;

use DateInterval;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use NumberNine\DataFixtures\BaseFixture;
use NumberNine\DataFixtures\FixtureSettings;
use NumberNine\Entity\Post;
use NumberNine\Entity\User;
use NumberNine\Model\Content\PublishingStatusInterface;

final class PostFixtures extends BaseFixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function loadData(ObjectManager $manager): void
    {
        /** @var User $administrator */
        $administrator = $this->getReference(User::class . '_administrator');
        /** @var User $editor */
        $editor = $this->getReference(User::class . '_editor');
        /** @var User $author */
        $author = $this->getReference(User::class . '_author');
        $authors = [$administrator, $editor, $author];

        $this->createManyContentEntity(
            'post',
            FixtureSettings::POSTS_COUNT,
            function (Post $post, $i) use ($authors) {
                $startDate = $this->faker->dateTimeInInterval($i === 0 ? '-5 years' : $this->getReference(Post::class . '_post_' . ($i - 1))->getCreatedAt(), '+1 day');

                $post
                    ->setTitle($this->faker->blogTitle)
                    ->setExcerpt($this->faker->paragraph)
                    ->setContent($this->faker->text(2000))
                    ->setAuthor($authors[array_rand($authors)])
                    ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
                    ->setCreatedAt($this->faker->dateTimeBetween($startDate, $startDate->add(DateInterval::createFromDateString('+1 month'))))
                    ->setPublishedAt($post->getCreatedAt());
            }
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['content'];
    }
}
