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

namespace NumberNine\Tests\Unit\Command;

use NumberNine\Repository\PostRepository;
use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\UserFactory;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class MakeDefaultPagesCommandTest extends KernelTestCase
{
    public function testExecuteWithoutAdminUser(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('numbernine:make:default-pages');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[ERROR] You must create an admin user', $output);
    }

    public function testExecuteWithNonExistentAdminUser(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('numbernine:make:default-pages');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['--username' => 'nonexistentuser']);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[ERROR] Unknown administrator user "nonexistentuser"', $output);
    }

    public function testExecuteResolvingDefaultAdminUser(): void
    {
        $kernel = static::createKernel();

        $userFactory = static::getContainer()->get(UserFactory::class);
        $userRoleRepository = static::getContainer()->get(UserRoleRepository::class);
        $postRepository = static::getContainer()->get(PostRepository::class);
        $adminRole = $userRoleRepository->findOneBy(['name' => 'Administrator']);
        $admin = $userFactory->createUser('admin', 'admin@numberninecms.com', 'password', [$adminRole]);

        $application = new Application($kernel);

        $command = $application->find('numbernine:make:default-pages');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] Default pages successfully created.', $output);

        $myAccount = $postRepository->findOneBy(['title' => 'My account']);
        $this->assertEquals($admin->getId(), $myAccount->getAuthor()->getId());

        $privacy = $postRepository->findOneBy(['title' => 'Privacy policy']);
        $this->assertEquals($admin->getId(), $privacy->getAuthor()->getId());
    }

    public function testExecuteWithAdminUserAsOption(): void
    {
        $kernel = static::createKernel();

        $userFactory = static::getContainer()->get(UserFactory::class);
        $userRoleRepository = static::getContainer()->get(UserRoleRepository::class);
        $postRepository = static::getContainer()->get(PostRepository::class);
        $adminRole = $userRoleRepository->findOneBy(['name' => 'Administrator']);
        $userFactory->createUser('admin', 'admin@numberninecms.com', 'password', [$adminRole]);
        $admin = $userFactory->createUser('anotheradmin', 'anotheradmin@numberninecms.com', 'password', [$adminRole]);

        $application = new Application($kernel);

        $command = $application->find('numbernine:make:default-pages');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['--username' => 'anotheradmin']);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] Default pages successfully created.', $output);

        $myAccount = $postRepository->findOneBy(['title' => 'My account']);
        $this->assertEquals($admin->getId(), $myAccount->getAuthor()->getId());

        $privacy = $postRepository->findOneBy(['title' => 'Privacy policy']);
        $this->assertEquals($admin->getId(), $privacy->getAuthor()->getId());
    }
}
