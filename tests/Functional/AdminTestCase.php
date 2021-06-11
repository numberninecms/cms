<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Admin\AdminMenuBuilderStore;
use NumberNine\Entity\UserRole;
use NumberNine\Model\Menu\Builder\AdminMenuBuilder;
use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\UserFactory;
use NumberNine\Tests\DotEnvAwareWebTestCase;

abstract class AdminTestCase extends DotEnvAwareWebTestCase
{
    protected UserRoleRepository $userRoleRepository;
    protected UserFactory $userFactory;
    protected EntityManagerInterface $entityManager;
    protected AdminMenuBuilder $adminMenuBuilder;
    protected UserRole $testRole;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRoleRepository = self::$container->get(UserRoleRepository::class); // @phpstan-ignore-line
        $this->userFactory = self::$container->get(UserFactory::class); // @phpstan-ignore-line
        $this->entityManager = self::$container->get(EntityManagerInterface::class); // @phpstan-ignore-line

        $this->testRole = (new UserRole())->setName('TestRole')->setCapabilities([]);
        $this->entityManager->persist($this->testRole); // @phpstan-ignore-line
        $this->entityManager->flush(); // @phpstan-ignore-line
    }

    protected function setCapabilitiesThenLogin(array $capabilities): void
    {
        $this->testRole->setCapabilities($capabilities);
        $this->entityManager->persist($this->testRole);
        $this->entityManager->flush();

        $this->loginThenNavigateToAdminDashboard('TestRole');
    }

    protected function loginThenNavigateToAdminDashboard(string $role): void
    {
        $this->loginAs($role);
        $this->client->request('GET', '/admin/');

        /** @var AdminMenuBuilderStore $adminMenuBuilderStore */
        $adminMenuBuilderStore = self::$container->get(AdminMenuBuilderStore::class);

        $this->adminMenuBuilder = $adminMenuBuilderStore->getAdminMenuBuilder();
    }

    protected function loginAs(string $role): void
    {
        $user = $this->userFactory->createUser(
            'test',
            'test@numbernine-fakedomain.com',
            'password',
            [$this->userRoleRepository->findOneBy(['name' => $role])],
        );

        $this->client->loginUser($user);
    }
}