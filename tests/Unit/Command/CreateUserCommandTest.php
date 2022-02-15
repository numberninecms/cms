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

use NumberNine\Entity\User;
use NumberNine\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 * @coversNothing
 */
final class CreateUserCommandTest extends KernelTestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    public function testExecuteWithoutArgument(): void
    {
        $kernel = static::createKernel();
        $command = (new Application($kernel))->find('numbernine:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['john', 'john@numberninecms.com', 'password']);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        static::assertSame($this->getFixtureContent('execute_without_argument'), trim($output));
    }

    public function testExecuteWithNameArgument(): void
    {
        $kernel = static::createKernel();
        $command = (new Application($kernel))->find('numbernine:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['john@numberninecms.com', 'password']);
        $commandTester->execute(['username' => 'john']);

        $output = $commandTester->getDisplay();
        static::assertSame($this->getFixtureContent('execute_with_name_argument'), trim($output));
    }

    public function testExecuteWithNameAndEmailArguments(): void
    {
        $kernel = static::createKernel();
        $command = (new Application($kernel))->find('numbernine:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['password']);
        $commandTester->execute(['username' => 'john', 'email' => 'john@numberninecms.com']);

        $output = $commandTester->getDisplay();
        static::assertSame($this->getFixtureContent('execute_with_name_and_email_arguments'), trim($output));
    }

    public function testExecuteWithNameEmailAndPasswordArguments(): void
    {
        $kernel = static::createKernel();
        $command = (new Application($kernel))->find('numbernine:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['username' => 'john', 'email' => 'john@numberninecms.com', 'password' => 'password']);

        $output = $commandTester->getDisplay();
        static::assertSame('[OK] User john has been created.', trim($output));
    }

    public function testCreateAdministratorWithOption(): void
    {
        $kernel = static::createKernel();
        $command = (new Application($kernel))->find('numbernine:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'username' => 'john',
            'email' => 'john@numberninecms.com',
            'password' => 'password',
            '--admin' => null,
        ]);

        $user = $this->userRepository->findOneByUsername('john');
        static::assertInstanceOf(User::class, $user);
        static::assertEquals(['Administrator'], $user->getRoles());
    }

    public function testDefaultRoleIsSubscriber(): void
    {
        $kernel = static::createKernel();
        $command = (new Application($kernel))->find('numbernine:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'username' => 'john',
            'email' => 'john@numberninecms.com',
            'password' => 'password',
        ]);

        $user = $this->userRepository->findOneByUsername('john');
        static::assertInstanceOf(User::class, $user);
        static::assertEquals(['Subscriber'], $user->getRoles());
    }

    public function testAssignCustomRoleToUser(): void
    {
        $kernel = static::createKernel();
        $command = (new Application($kernel))->find('numbernine:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'username' => 'john',
            'email' => 'john@numberninecms.com',
            'password' => 'password',
            '--roles' => ['Contributor'],
        ]);

        $user = $this->userRepository->findOneByUsername('john');
        static::assertInstanceOf(User::class, $user);
        static::assertEquals(['Contributor'], $user->getRoles());
    }

    public function testAssignMultipleRolesToUser(): void
    {
        $kernel = static::createKernel();
        $command = (new Application($kernel))->find('numbernine:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'username' => 'john',
            'email' => 'john@numberninecms.com',
            'password' => 'password',
            '--roles' => ['Contributor', 'Editor'],
        ]);

        $user = $this->userRepository->findOneByUsername('john');
        static::assertInstanceOf(User::class, $user);
        static::assertEquals(['Contributor', 'Editor'], $user->getRoles());
    }

    private function getFixtureContent(string $name): string
    {
        return trim(
            file_get_contents(sprintf('%s/../../Fixtures/Command/CreateUserCommand/%s.txt', __DIR__, $name))
        );
    }
}
