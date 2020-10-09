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

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use NumberNine\DataFixtures\BaseFixture;
use NumberNine\DataFixtures\FixtureSettings;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\ContentEntityTerm;
use NumberNine\Entity\Post;
use NumberNine\Entity\Taxonomy;
use NumberNine\Entity\Term;

final class ContentEntityTermFixtures extends BaseFixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            ContentEntityTerm::class,
            FixtureSettings::POSTS_COUNT,
            function (ContentEntityTerm $contentEntityTerm, $i) {
                $contentEntityTerm
                    ->setContentEntity($this->getReference(Post::class . '_post_' . $i))
                    ->setTerm($this->getReference(Term::class . '_' . (random_int(0, FixtureSettings::CATEGORIES_COUNT - 1))));
            }
        );

        $this->createMany(
            ContentEntityTerm::class,
            FixtureSettings::POSTS_COUNT * 2,
            function (ContentEntityTerm $contentEntityTerm, $i) {
                $contentEntityTerm
                    ->setContentEntity($this->getReference(Post::class . '_post_' . (int)floor(($i - FixtureSettings::POSTS_COUNT) / 2)));

                if ($i % 2 === 0) {
                    $contentEntityTerm
                        ->setTerm($this->getReference(Term::class . '_' . (random_int(FixtureSettings::CATEGORIES_COUNT, FixtureSettings::TAGS_COUNT / 2))));
                } else {
                    $contentEntityTerm
                        ->setTerm($this->getReference(Term::class . '_' . (random_int(FixtureSettings::CATEGORIES_COUNT + FixtureSettings::TAGS_COUNT / 2, FixtureSettings::TAGS_COUNT))));
                }
            },
            FixtureSettings::POSTS_COUNT
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [PostFixtures::class, TermFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['content'];
    }
}
