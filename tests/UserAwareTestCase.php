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
use Symfony\Component\Uid\Uuid;

abstract class UserAwareTestCase extends DotEnvAwareWebTestCase
{
    protected UserRoleRepository $userRoleRepository;
    protected UserFactory $userFactory;
    protected EntityManagerInterface $entityManager;
    protected AdminMenuBuilder $adminMenuBuilder;
    protected UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRoleRepository = static::getContainer()->get(UserRoleRepository::class);
        $this->userFactory = static::getContainer()->get(UserFactory::class);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->urlGenerator = static::getContainer()->get(UrlGeneratorInterface::class);
    }

    public function loginThenNavigateToAdminUrl(
        User|array|string $userOrRoleOrCapabilities,
        ?string $url = null,
        string $method = 'GET',
    ): User {
        if ($url && !str_starts_with($url, '/admin/')) {
            static::fail('$url parameter must be an admin URL.');
        }

        $user = $this->loginAs($userOrRoleOrCapabilities);
        $this->client->request($method, $url ?? '/admin/');

        /** @var AdminMenuBuilderStore $adminMenuBuilderStore */
        $adminMenuBuilderStore = static::getContainer()->get(AdminMenuBuilderStore::class);

        $this->adminMenuBuilder = $adminMenuBuilderStore->getAdminMenuBuilder();

        return $user;
    }

    protected function setCapabilitiesThenLogin(array $capabilities): User
    {
        $role = $this->createRole($capabilities);

        return $this->loginThenNavigateToAdminUrl($role->getName());
    }

    protected function loginAs(User|array|string $userOrRoleOrCapabilities): User
    {
        if (\is_string($userOrRoleOrCapabilities) || \is_array($userOrRoleOrCapabilities)) {
            $userOrRoleOrCapabilities = $this->createUser($userOrRoleOrCapabilities);
        }

        $this->client->loginUser($userOrRoleOrCapabilities);

        return $userOrRoleOrCapabilities;
    }

    protected function createUser(string|array $roleOrCapabilities = []): User
    {
        if (\is_string($roleOrCapabilities)) {
            $username = Uuid::v4();

            return $this->userFactory->createUser(
                $username,
                $username . '@numbernine-fakedomain.com',
                'password',
                [$this->userRoleRepository->findOneBy(['name' => $roleOrCapabilities])],
            );
        }

        $role = $this->createRole($roleOrCapabilities);

        return $this->createUser($role->getName());
    }

    private function createRole(array $capabilities = []): UserRole
    {
        $role = (new UserRole())
            ->setName(Uuid::v4())
            ->setCapabilities($capabilities)
        ;

        $this->entityManager->persist($role);
        $this->entityManager->flush();

        return $role;
    }
}
