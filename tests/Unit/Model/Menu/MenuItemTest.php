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

namespace NumberNine\Tests\Unit\Model\Menu;

use NumberNine\Model\Menu\MenuItem;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * @internal
 * @coversNothing
 */
final class MenuItemTest extends TestCase
{
    public function testEmptyArguments(): void
    {
        self::expectException(MissingOptionsException::class);
        new MenuItem();
    }

    public function testMissingTextArgument(): void
    {
        self::expectException(MissingOptionsException::class);
        new MenuItem(['link' => '/admin/']);
    }

    public function testMinimumRequiredArguments(): void
    {
        static::assertInstanceOf(MenuItem::class, new MenuItem(['text' => 'Sample menu item']));
    }

    public function testStandardMenuItem(): void
    {
        $menuItem = new MenuItem([
            'text' => 'Dashboard',
            'link' => '/admin/',
            'icon' => 'tachometer-alt',
        ]);

        static::assertSame('Dashboard', $menuItem->getText());
        static::assertSame('/admin/', $menuItem->getLink());
        static::assertSame('tachometer-alt', $menuItem->getIcon());
        static::assertSame(0, $menuItem->getPosition());
        static::assertIsArray($menuItem->getChildren());
        static::assertEmpty($menuItem->getChildren());
    }

    public function testMenuItemWithChildrenAsRawArray(): void
    {
        $menuItem = new MenuItem([
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

        static::assertTrue($menuItem->hasChildren());
        static::assertCount(2, $menuItem->getChildren());
        static::assertInstanceOf(MenuItem::class, $menuItem->getChildren()['general']);
        static::assertSame(200, $menuItem->getChildren()['permalinks']->getPosition());
    }

    public function testMenuItemWithChildrenAsMenuItemArray(): void
    {
        $menuItem = new MenuItem([
            'text' => 'Settings',
            'link' => '/admin/settings/',
            'icon' => 'cogs',
            'children' => [
                'general' => new MenuItem([
                    'text' => 'General',
                    'link' => '/admin/settings/general/',
                ]),
                'permalinks' => new MenuItem([
                    'text' => 'Permalinks',
                    'link' => '/admin/settings/permalinks/',
                ]),
            ],
        ]);

        static::assertTrue($menuItem->hasChildren());
        static::assertCount(2, $menuItem->getChildren());
        static::assertInstanceOf(MenuItem::class, $menuItem->getChildren()['general']);
        static::assertSame(200, $menuItem->getChildren()['permalinks']->getPosition());
    }

    public function testMenuItemAddChild(): void
    {
        $menuItem = new MenuItem([
            'text' => 'Settings',
            'link' => '/admin/settings/',
            'icon' => 'cogs',
        ]);

        $menuItem->addChild('general', new MenuItem([
            'text' => 'General',
            'link' => '/admin/settings/general/',
        ]));

        $menuItem->addChild('permalinks', new MenuItem([
            'text' => 'Permalinks',
            'link' => '/admin/settings/permalinks/',
        ]));

        static::assertTrue($menuItem->hasChildren());
        static::assertCount(2, $menuItem->getChildren());
        static::assertInstanceOf(MenuItem::class, $menuItem->getChildren()['general']);
        static::assertSame(200, $menuItem->getChildren()['permalinks']->getPosition());
    }
}
