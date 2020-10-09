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
use Doctrine\Persistence\ObjectManager;
use NumberNine\DataFixtures\BaseFixture;
use NumberNine\Entity\Taxonomy;

final class TaxonomyFixtures extends BaseFixture implements FixtureGroupInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $taxonomyCategory = (new Taxonomy())
            ->setName('category')
            ->setContentTypes(['post']);
        $this->setReference('taxonomy_category', $taxonomyCategory);

        $taxonomyTag = (new Taxonomy())
            ->setName('tag')
            ->setContentTypes(['post']);
        $this->setReference('taxonomy_tag', $taxonomyTag);

        $manager->persist($taxonomyCategory);
        $manager->persist($taxonomyTag);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['content'];
    }
}
