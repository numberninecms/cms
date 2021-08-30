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

namespace NumberNine\Tests\Functional\Model\Menu\Builder;

use NumberNine\Security\Capabilities;
use NumberNine\Tests\Functional\UserAwareTestCase;

final class AdminMenuBuilderTest extends UserAwareTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testNoCapabilityEditMediaFiles(): void
    {
        $this->setCapabilitiesThenLogin([]);
        self::assertArrayNotHasKey('media_library', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityEditMediaFiles(): void
    {
        $this->setCapabilitiesThenLogin(['edit_media_files']);
        self::assertArrayHasKey('media_library', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityEditBlocks(): void
    {
        $this->setCapabilitiesThenLogin([]);
        self::assertArrayNotHasKey('block_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityEditBlocks(): void
    {
        $this->setCapabilitiesThenLogin(['edit_blocks']);
        self::assertArrayHasKey('block_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityEditPosts(): void
    {
        $this->setCapabilitiesThenLogin([]);
        self::assertArrayNotHasKey('post_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityEditPosts(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::EDIT_POSTS]);
        self::assertArrayHasKey('post_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityEditPages(): void
    {
        $this->setCapabilitiesThenLogin([]);
        self::assertArrayNotHasKey('page_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityEditPages(): void
    {
        $this->setCapabilitiesThenLogin(['edit_pages']);
        self::assertArrayHasKey('page_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityListUsers(): void
    {
        $this->setCapabilitiesThenLogin([]);
        self::assertArrayNotHasKey('users', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityListUsers(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS]);
        self::assertArrayHasKey('users', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityCreateUsers(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS]);
        self::assertArrayNotHasKey('add_new', $this->adminMenuBuilder->getMenuItems()['users']->getChildren());
    }

    public function testHasCapabilityCreateUsers(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS, Capabilities::CREATE_USERS]);
        self::assertArrayHasKey('add_new', $this->adminMenuBuilder->getMenuItems()['users']->getChildren());
    }

    public function testNoCapabilityManageRoles(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS]);
        self::assertArrayNotHasKey('roles', $this->adminMenuBuilder->getMenuItems()['users']->getChildren());
    }

    public function testHasCapabilityManageRoles(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS, Capabilities::MANAGE_ROLES]);
        self::assertArrayHasKey('roles', $this->adminMenuBuilder->getMenuItems()['users']->getChildren());
    }

    public function testNoCapabilityManageOptions(): void
    {
        $this->setCapabilitiesThenLogin([]);
        self::assertArrayNotHasKey('settings', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityManageOptions(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::MANAGE_OPTIONS]);
        self::assertArrayHasKey('settings', $this->adminMenuBuilder->getMenuItems());
        self::assertArrayHasKey('general', $this->adminMenuBuilder->getMenuItems()['settings']->getChildren());
        self::assertArrayHasKey('permalinks', $this->adminMenuBuilder->getMenuItems()['settings']->getChildren());
        self::assertArrayHasKey('email', $this->adminMenuBuilder->getMenuItems()['settings']->getChildren());
    }

    public function testNoCapabilityCustomize(): void
    {
        $this->setCapabilitiesThenLogin([]);
        self::assertArrayNotHasKey('appearance', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityCustomize(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::CUSTOMIZE]);
        self::assertArrayHasKey('appearance', $this->adminMenuBuilder->getMenuItems());
        self::assertArrayHasKey('areas', $this->adminMenuBuilder->getMenuItems()['appearance']->getChildren());
        self::assertArrayHasKey('menus', $this->adminMenuBuilder->getMenuItems()['appearance']->getChildren());
    }
}
