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

use NumberNine\Repository\UserRepository;
use NumberNine\Tests\Functional\DotEnvAwareWebTestCase;

class AdminIndexActionWebTest extends DotEnvAwareWebTestCase
{
    public function testAdminPageRedirectsToLogin(): void
    {
        $this->client->request('GET', '/admin/');
        self::assertResponseRedirects('/admin/login');
    }

    public function testAdminLoginIsSuccessful(): void
    {
        $userRepository = self::$container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByUsername('admin');

        $this->client->loginUser($adminUser);

        $this->client->request('GET', '/admin/');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div#q-app');
    }

    public function testSubscriberCantAccessAdmin(): void
    {
        $userRepository = self::$container->get(UserRepository::class);
        $subscriberUser = $userRepository->findOneByUsername('subscriber');

        $this->client->loginUser($subscriberUser);

        $this->client->request('GET', '/admin/');
        self::assertResponseRedirects('/');
    }
}
