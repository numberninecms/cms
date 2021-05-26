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

use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\UserFactory;
use NumberNine\Tests\DotEnvAwareWebTestCase;

class AdminIndexActionWebTest extends DotEnvAwareWebTestCase
{
    public function testAdminPageRedirectsToLogin(): void
    {
        $this->client->request('GET', '/admin/');
        self::assertResponseRedirects('/admin/login');
    }

    public function testAdminLoginIsSuccessful(): void
    {
        /** @var UserRoleRepository $userRoleRepository */
        $userRoleRepository = self::$container->get(UserRoleRepository::class);
        /** @var UserFactory $userFactory */
        $userFactory = self::$container->get(UserFactory::class);

        $adminUser = $userFactory->createUser(
            'admin',
            'admin@numbernine-fakedomain.com',
            'password',
            [$userRoleRepository->findOneBy(['name' => 'Administrator'])],
        );

        $this->client->loginUser($adminUser);

        $this->client->request('GET', '/admin/');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('div.ui-area');
    }

    public function testSubscriberCantAccessAdmin(): void
    {
        /** @var UserRoleRepository $userRoleRepository */
        $userRoleRepository = self::$container->get(UserRoleRepository::class);
        /** @var UserFactory $userFactory */
        $userFactory = self::$container->get(UserFactory::class);

        $subscriberUser = $userFactory->createUser(
            'subscriber',
            'subscriber@numbernine-fakedomain.com',
            'password',
            [$userRoleRepository->findOneBy(['name' => 'Subscriber'])],
        );

        $this->client->loginUser($subscriberUser);

        $this->client->request('GET', '/admin/');
        self::assertResponseRedirects('/');
    }
}
