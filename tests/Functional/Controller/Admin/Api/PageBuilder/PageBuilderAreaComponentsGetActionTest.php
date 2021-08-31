<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional\Controller\Admin\Api\PageBuilder;

use NumberNine\Security\Capabilities;
use NumberNine\Tests\UserAwareTestCase;

final class PageBuilderAreaComponentsGetActionTest extends UserAwareTestCase
{
    public function testNotLoggedInUserCantAccessUrl(): void
    {
        $this->client->request('GET', '/admin/api/page_builder/header/components');
        self::assertResponseRedirects('/admin/login');
    }

    public function testNonAllowedUserCantAccessUrl(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::ACCESS_ADMIN]);
        $this->client->request('GET', '/admin/api/page_builder/header/components');
        self::assertResponseRedirects('/');
    }

    public function testResponseIsSuccessfulForAllowedUser(): void
    {
        $this->setCapabilitiesThenLogin([Capabilities::ACCESS_ADMIN, Capabilities::CUSTOMIZE]);
        $this->client->request('GET', '/admin/api/page_builder/header/components');
        self::assertResponseIsSuccessful();
    }
}
