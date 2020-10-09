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

use DateTime;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use NumberNine\DataFixtures\BaseFixture;
use NumberNine\Entity\Post;
use NumberNine\Entity\User;
use NumberNine\Model\Content\PublishingStatusInterface;

final class PageFixtures extends BaseFixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const PAGES = ['Home', 'About', 'Contact', 'Our stores', 'Blog', 'Faq', 'My account', 'Privacy'];

    public function loadData(ObjectManager $manager): void
    {
        /** @var User $admin */
        $admin = $this->getReference(User::class . '_administrator');

        foreach (self::PAGES as $pageName) {
            $page = (new Post())
                ->setCustomType('page')
                ->setTitle($pageName)
                ->setContent($this->faker->text(2000))
                ->setAuthor($admin)
                ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
                ->setCreatedAt(new DateTime())
                ->setPublishedAt(new DateTime());

            $this->setReference(Post::class . '_page_' . strtolower(str_replace(' ', '_', $pageName)), $page);
            $manager->persist($page);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['content'];
    }
}
