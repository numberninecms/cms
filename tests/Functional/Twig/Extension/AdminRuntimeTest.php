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

namespace NumberNine\Tests\Functional\Twig\Extension;

use NumberNine\Entity\Post;
use NumberNine\Model\Menu\MenuItem;
use NumberNine\Tests\Functional\UserAwareTestCase;
use NumberNine\Twig\Extension\AdminRuntime;

final class AdminRuntimeTest extends UserAwareTestCase
{
    private AdminRuntime $runtime;

    protected function setUp(): void
    {
        parent::setUp();
        $this->runtime = static::getContainer()->get(AdminRuntime::class);
    }

    public function testGetAdminMenuItems(): void
    {
        $this->loginThenNavigateToAdminUrl('Administrator');
        $menuItems = $this->runtime->getAdminMenuItems();
        self::assertContainsOnlyInstancesOf(MenuItem::class, $menuItems);
        self::assertGreaterThanOrEqual(8, count($menuItems));
    }

    public function testGetHighlightedPermalinkUrlForPost(): void
    {
        $this->loginThenNavigateToAdminUrl('Administrator');
        $post = (new Post())
            ->setTitle('Title for this post')
            ->setCustomType('post')
            ->setCreatedAt(new \DateTime('2021/06/12'))
        ;

        $permalink = $this->runtime->getHighlightedPermalinkUrl($post);

        self::assertEquals('http://localhost/2021/06/12/<span class="slug">new-post</span>', $permalink);
    }

    public function testGetHighlightedPermalinkUrlForPage(): void
    {
        $this->loginThenNavigateToAdminUrl('Administrator');
        $page = (new Post())
            ->setTitle('Title for this page')
            ->setCustomType('page')
            ->setCreatedAt(new \DateTime('2021/06/12'))
        ;

        $permalink = $this->runtime->getHighlightedPermalinkUrl($page);

        self::assertEquals('http://localhost/<span class="slug">new-page</span>', $permalink);
    }
}
