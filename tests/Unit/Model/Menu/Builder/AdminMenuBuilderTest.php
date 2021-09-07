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

namespace NumberNine\Tests\Unit\Model\Menu\Builder;

use NumberNine\Model\Menu\Builder\AdminMenuBuilder;
use NumberNine\Model\Menu\MenuItem;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class AdminMenuBuilderTest extends TestCase
{
    private AdminMenuBuilder $adminMenuBuilder;

    protected function setUp(): void
    {
        $this->adminMenuBuilder = new AdminMenuBuilder();
    }

    public function testCreateSimpleMenuItem(): void
    {
        $this->adminMenuBuilder->append('dashboard', [
            'text' => 'Dashboard',
            'link' => '/admin/',
            'icon' => 'tachometer-alt',
        ]);

        $menuItems = $this->adminMenuBuilder->getMenuItems();

        static::assertArrayHasKey('dashboard', $menuItems);
        static::assertSame('Dashboard', $menuItems['dashboard']->getText());
        static::assertSame('/admin/', $menuItems['dashboard']->getLink());
        static::assertSame('tachometer-alt', $menuItems['dashboard']->getIcon());
    }

    public function testCreateSimpleMenuItemWithChildren(): void
    {
        $this->adminMenuBuilder->append('settings', [
            'text' => 'Settings',
            'link' => '/admin/settings/',
            'icon' => 'cogs',
            'children' => [
                'general' => [
                    'text' => 'General',
                    'link' => '/admin/settings/general/',
                ],
                'permalinks' => [
                    'text' => 'Permalinks',
                    'link' => '/admin/settings/permalinks/',
                ],
            ],
        ]);

        $menuItems = $this->adminMenuBuilder->getMenuItems();

        static::assertCount(2, $menuItems['settings']->getChildren());
        static::assertInstanceOf(MenuItem::class, $menuItems['settings']->getChildren()['general']);
    }

    public function testAppendSibling(): void
    {
        $this->adminMenuBuilder->append('dashboard', [
            'text' => 'Dashboard',
            'link' => '/admin/',
            'icon' => 'tachometer-alt',
        ]);

        $this->adminMenuBuilder->append('settings', [
            'text' => 'Settings',
            'link' => '/admin/settings/',
            'icon' => 'cogs',
        ]);

        $menuItems = $this->adminMenuBuilder->getMenuItems();

        static::assertCount(2, $menuItems);
        static::assertArrayHasKey('dashboard', $menuItems);
        static::assertArrayHasKey('settings', $menuItems);
    }

    public function testInsertAfter(): void
    {
        $this->adminMenuBuilder->append('dashboard', [
            'text' => 'Dashboard',
            'link' => '/admin/',
            'icon' => 'tachometer-alt',
        ]);

        $this->adminMenuBuilder->append('settings', [
            'text' => 'Settings',
            'link' => '/admin/settings/',
            'icon' => 'cogs',
        ]);

        $this->adminMenuBuilder->insertAfter('dashboard', 'users', [
            'text' => 'Users',
            'link' => '/admin/users/',
            'icon' => 'users',
        ]);

        $menuItems = $this->adminMenuBuilder->getMenuItems();

        static::assertCount(3, $menuItems);
        static::assertSame(['dashboard', 'users', 'settings'], array_keys($menuItems));
    }

    public function testInsertBefore(): void
    {
        $this->adminMenuBuilder->append('dashboard', [
            'text' => 'Dashboard',
            'link' => '/admin/',
            'icon' => 'tachometer-alt',
        ]);

        $this->adminMenuBuilder->append('settings', [
            'text' => 'Settings',
            'link' => '/admin/settings/',
            'icon' => 'cogs',
        ]);

        $this->adminMenuBuilder->insertBefore('settings', 'users', [
            'text' => 'Users',
            'link' => '/admin/users/',
            'icon' => 'users',
        ]);

        $menuItems = $this->adminMenuBuilder->getMenuItems();

        static::assertCount(3, $menuItems);
        static::assertSame(['dashboard', 'users', 'settings'], array_keys($menuItems));
    }

    public function testInsertAfterChild(): void
    {
        $this->adminMenuBuilder->append('settings', [
            'text' => 'Settings',
            'link' => '/admin/settings/',
            'icon' => 'cogs',
            'children' => [
                'general' => [
                    'text' => 'General',
                    'link' => '/admin/settings/general/',
                ],
                'permalinks' => [
                    'text' => 'Permalinks',
                    'link' => '/admin/settings/permalinks/',
                ],
            ],
        ]);

        $this->adminMenuBuilder->insertAfter('settings.general', 'emails', [
            'text' => 'Emails',
            'link' => '/admin/settings/emails/',
        ]);

        $menuItems = $this->adminMenuBuilder->getMenuItems();

        static::assertCount(1, $menuItems);
        static::assertCount(3, $menuItems['settings']->getChildren());
        static::assertSame(['general', 'emails', 'permalinks'], array_keys($menuItems['settings']->getChildren()));
    }
}
