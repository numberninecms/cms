<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Admin\AdminMenuBuilderStore;
use NumberNine\Entity\User;
use NumberNine\Entity\UserRole;
use NumberNine\Model\Menu\Builder\AdminMenuBuilder;
use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\UserFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class UserAwareTestCase extends DotEnvAwareWebTestCase
{
    protected UserRoleRepository $userRoleRepository;
    protected UserFactory $userFactory;
    protected EntityManagerInterface $entityManager;
    protected AdminMenuBuilder $adminMenuBuilder;
    protected UrlGeneratorInterface $urlGenerator;
    protected UserRole $testRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRoleRepository = static::getContainer()->get(UserRoleRepository::class); // @phpstan-ignore-line
        $this->userFactory = static::getContainer()->get(UserFactory::class); // @phpstan-ignore-line
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class); // @phpstan-ignore-line
        $this->urlGenerator = static::getContainer()->get(UrlGeneratorInterface::class); // @phpstan-ignore-line

        $this->testRole = (new UserRole())->setName('TestRole')->setCapabilities([]);
        $this->entityManager->persist($this->testRole); // @phpstan-ignore-line
        $this->entityManager->flush(); // @phpstan-ignore-line
    }

    public function loginThenNavigateToAdminUrl(User|string $userOrRole, ?string $url = null): User
    {
        if ($url && !str_starts_with($url, '/admin/')) {
            static::fail('$url parameter must be an admin URL.');
        }

        $user = $this->loginAs($userOrRole);
        $this->client->request('GET', $url ?? '/admin/');

        /** @var AdminMenuBuilderStore $adminMenuBuilderStore */
        $adminMenuBuilderStore = static::getContainer()->get(AdminMenuBuilderStore::class);

        $this->adminMenuBuilder = $adminMenuBuilderStore->getAdminMenuBuilder();

        return $user;
    }

    protected function setCapabilitiesThenLogin(array $capabilities): User
    {
        $this->testRole->setCapabilities($capabilities);
        $this->entityManager->persist($this->testRole);
        $this->entityManager->flush();

        return $this->loginThenNavigateToAdminUrl('TestRole');
    }

    protected function loginAs(User|string $userOrRole): User
    {
        if (\is_string($userOrRole)) {
            $userOrRole = $this->createUser($userOrRole);
        }

        $this->client->loginUser($userOrRole);

        return $userOrRole;
    }

    protected function createUser(string|array $roleOrCapabilities): User
    {
        if (\is_string($roleOrCapabilities)) {
            return $this->userFactory->createUser(
                strtolower($roleOrCapabilities),
                strtolower($roleOrCapabilities) . '@numbernine-fakedomain.com',
                'password',
                [$this->userRoleRepository->findOneBy(['name' => $roleOrCapabilities])],
            );
        }

        $this->testRole->setCapabilities($roleOrCapabilities);
        $this->entityManager->persist($this->testRole);
        $this->entityManager->flush();

        return $this->createUser('TestRole');
    }
}
