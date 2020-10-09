<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\DataFixtures\Layout;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use NumberNine\DataFixtures\BaseFixture;
use NumberNine\DataFixtures\Content\PageFixtures;
use NumberNine\Entity\CoreOption;
use NumberNine\Entity\Post;
use NumberNine\Model\General\Settings;

final class SettingFixtures extends BaseFixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $siteTitle = (new CoreOption())->setName(Settings::SITE_TITLE)->setValue('My awesome website');
        $myAccount = (new CoreOption())->setName(Settings::PAGE_FOR_MY_ACCOUNT)->setValue($this->getReference(Post::class . '_page_my_account')->getId());
        $privacy = (new CoreOption())->setName(Settings::PAGE_FOR_PRIVACY)->setValue($this->getReference(Post::class . '_page_privacy')->getId());

        $manager->persist($siteTitle);
        $manager->persist($myAccount);
        $manager->persist($privacy);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [PageFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['layout'];
    }
}
