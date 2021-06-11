<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional\Controller\Admin;

use NumberNine\Security\Capabilities;
use NumberNine\Tests\Functional\AdminTestCase;

class AdminIndexActionWebTest extends AdminTestCase
{
    public function testAdminPageRedirectsToLogin(): void
    {
        $this->client->request('GET', '/admin/');
        self::assertResponseRedirects('/admin/login');
    }

    public function testAdministratorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminDashboard('Administrator');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testEditorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminDashboard('Editor');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testAuthorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminDashboard('Author');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testContributorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminDashboard('Contributor');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testSubscriberCannotAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminDashboard('Subscriber');
        self::assertResponseRedirects('/');
    }

    public function testNoCapabilityCannotAccessAdmin(): void
    {
        $this->setCapabilitiesThenLogin([]);
        self::assertResponseRedirects('/');
    }

    public function testAccessAdminCapabilityCanAccessAdmin(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::ACCESS_ADMIN]);
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }
}
