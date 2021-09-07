<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional\Controller\Admin\Ui\Dashboard;

use NumberNine\Security\Capabilities;
use NumberNine\Tests\UserAwareTestCase;

/**
 * @internal
 * @coversNothing
 */
final class DashboardIndexActionTest extends UserAwareTestCase
{
    public function testAdminPageRedirectsToLogin(): void
    {
        $this->client->request('GET', '/admin/');
        self::assertResponseRedirects('/admin/login');
    }

    public function testAdministratorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminUrl('Administrator');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testEditorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminUrl('Editor');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testAuthorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminUrl('Author');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testContributorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminUrl('Contributor');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testSubscriberCannotAccessAdmin(): void
    {
        $this->loginThenNavigateToAdminUrl('Subscriber');
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
