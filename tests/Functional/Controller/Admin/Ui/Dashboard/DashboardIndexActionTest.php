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

use NumberNine\Bundle\Test\UserAwareTestCase;
use NumberNine\Security\Capabilities;

/**
 * @internal
 * @coversNothing
 */
final class DashboardIndexActionTest extends UserAwareTestCase
{
    public function testAdminPageRedirectsToLogin(): void
    {
        $this->client->request('GET', sprintf('/%s/', $this->adminUrlPrefix));
        self::assertResponseRedirects(sprintf('/%s/login', $this->adminUrlPrefix), 302);
    }

    public function testAdministratorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToUrl('Administrator');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testEditorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToUrl('Editor');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testAuthorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToUrl('Author');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testContributorCanAccessAdmin(): void
    {
        $this->loginThenNavigateToUrl('Contributor');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testSubscriberCannotAccessAdmin(): void
    {
        $this->loginThenNavigateToUrl('Subscriber');
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
