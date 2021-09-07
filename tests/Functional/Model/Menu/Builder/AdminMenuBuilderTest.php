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
use NumberNine\Tests\UserAwareTestCase;

/**
 * @internal
 * @coversNothing
 */
final class AdminMenuBuilderTest extends UserAwareTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testNoCapabilityEditMediaFiles(): void
    {
        $this->setCapabilitiesThenLogin([]);
        static::assertArrayNotHasKey('media_library', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityEditMediaFiles(): void
    {
        $this->setCapabilitiesThenLogin(['edit_media_files']);
        static::assertArrayHasKey('media_library', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityEditBlocks(): void
    {
        $this->setCapabilitiesThenLogin([]);
        static::assertArrayNotHasKey('block_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityEditBlocks(): void
    {
        $this->setCapabilitiesThenLogin(['edit_blocks']);
        static::assertArrayHasKey('block_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityEditPosts(): void
    {
        $this->setCapabilitiesThenLogin([]);
        static::assertArrayNotHasKey('post_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityEditPosts(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::EDIT_POSTS]);
        static::assertArrayHasKey('post_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityEditPages(): void
    {
        $this->setCapabilitiesThenLogin([]);
        static::assertArrayNotHasKey('page_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityEditPages(): void
    {
        $this->setCapabilitiesThenLogin(['edit_pages']);
        static::assertArrayHasKey('page_entity', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityListUsers(): void
    {
        $this->setCapabilitiesThenLogin([]);
        static::assertArrayNotHasKey('users', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityListUsers(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS]);
        static::assertArrayHasKey('users', $this->adminMenuBuilder->getMenuItems());
    }

    public function testNoCapabilityCreateUsers(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS]);
        static::assertArrayNotHasKey('add_new', $this->adminMenuBuilder->getMenuItems()['users']->getChildren());
    }

    public function testHasCapabilityCreateUsers(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS, Capabilities::CREATE_USERS]);
        static::assertArrayHasKey('add_new', $this->adminMenuBuilder->getMenuItems()['users']->getChildren());
    }

    public function testNoCapabilityManageRoles(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS]);
        static::assertArrayNotHasKey('roles', $this->adminMenuBuilder->getMenuItems()['users']->getChildren());
    }

    public function testHasCapabilityManageRoles(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::LIST_USERS, Capabilities::MANAGE_ROLES]);
        static::assertArrayHasKey('roles', $this->adminMenuBuilder->getMenuItems()['users']->getChildren());
    }

    public function testNoCapabilityManageOptions(): void
    {
        $this->setCapabilitiesThenLogin([]);
        static::assertArrayNotHasKey('settings', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityManageOptions(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::MANAGE_OPTIONS]);
        static::assertArrayHasKey('settings', $this->adminMenuBuilder->getMenuItems());
        static::assertArrayHasKey('general', $this->adminMenuBuilder->getMenuItems()['settings']->getChildren());
        static::assertArrayHasKey('permalinks', $this->adminMenuBuilder->getMenuItems()['settings']->getChildren());
        static::assertArrayHasKey('email', $this->adminMenuBuilder->getMenuItems()['settings']->getChildren());
    }

    public function testNoCapabilityCustomize(): void
    {
        $this->setCapabilitiesThenLogin([]);
        static::assertArrayNotHasKey('appearance', $this->adminMenuBuilder->getMenuItems());
    }

    public function testHasCapabilityCustomize(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::CUSTOMIZE]);
        static::assertArrayHasKey('appearance', $this->adminMenuBuilder->getMenuItems());
        static::assertArrayHasKey('areas', $this->adminMenuBuilder->getMenuItems()['appearance']->getChildren());
        static::assertArrayHasKey('menus', $this->adminMenuBuilder->getMenuItems()['appearance']->getChildren());
    }
}
