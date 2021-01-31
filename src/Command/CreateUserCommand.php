<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Command;

use Exception;
use NumberNine\Repository\UserRoleRepository;
use NumberNine\Security\UserFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CreateUserCommand extends Command
{
    protected static $defaultName = 'numbernine:user:create';

    private UserFactory $userFactory;
    private UserRoleRepository $userRoleRepository;

    public function __construct(UserFactory $userFactory, UserRoleRepository $userRoleRepository)
    {
        parent::__construct();
        $this->userFactory = $userFactory;
        $this->userRoleRepository = $userRoleRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user')
            ->setHelp('This command allows you to create a user')
            ->addArgument('username', InputArgument::OPTIONAL, 'Username')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Admin role for this user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!$username || !$email || !$password) {
            $io->title('Create a new user');

            if (!$username) {
                $username = $io->ask('Choose a username');
            }

            if (!$email) {
                $email = $io->ask('Choose an email');
            }

            if (!$password) {
                $password = $io->askHidden('Choose a password');
            }
        }

        $isAdmin = $input->getOption('admin');
        $adminRole = $this->userRoleRepository->findOneBy(['name' => 'Administrator']);
        $subscriberRole = $this->userRoleRepository->findOneBy(['name' => 'Subscriber']);
        $roles = ($isAdmin && $adminRole) ? [$adminRole] : [$subscriberRole];

        try {
            $this->userFactory->createUser($username, $email, $password, $roles);
        } catch (Exception $e) {
            $io->error('User cannot be created. This username is probably already in use.');
            $io->writeln($e->getMessage());
            return 0;
        }

        $io->success("User $username has been created.");

        return 0;
    }
}
