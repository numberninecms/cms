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

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use NumberNine\Entity\Menu;
use NumberNine\Entity\Post;

final class MenuFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $top = (new Menu())->setName('Top menu');
        $main = (new Menu())->setName('Main menu');
        $footer = (new Menu())->setName('Footer menu');

        $pages = ['Home', 'About', 'Contact', 'Blog', 'Faq'];
        $items = [];

        foreach ($pages as $pageName) {
            $items[] = [
                'title' => $pageName,
                'children' => [],
                'entityId' => $this->getReference(
                    Post::class . '_page_' . strtolower(str_replace(' ', '_', $pageName))
                )->getId(),
            ];
        }

        $main->setMenuItems($items);

        $manager->persist($top);
        $manager->persist($main);
        $manager->persist($footer);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['layout'];
    }

    public function getDependencies()
    {
        return [PageFixtures::class];
    }
}
