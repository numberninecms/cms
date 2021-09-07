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
use NumberNine\Media\MediaFileFactory;
use NumberNine\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MediaAddCommand extends Command
{
    protected static $defaultName = 'numbernine:media:add';

    public function __construct(private MediaFileFactory $mediaFileFactory, private UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Adds a file to the media library')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Absolute filename of the file to add')
            ->addArgument('username', InputArgument::OPTIONAL, "Username of the media file's author")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');
        $username = $input->getArgument('username');

        if (!$filename || !$username) {
            $io->title('Add a file to the media library');

            if (!$filename) {
                $filename = $io->ask('Absolute filename');
            }

            if (!$username) {
                $username = $io->ask('Username of the author');
            }
        }

        $filename = trim($filename, " \t\n\r\0\x0B\"");
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $io->error('Unknown user');

            return 1;
        }

        try {
            $this->mediaFileFactory->createMediaFileFromFilename($filename, $user);
        } catch (Exception $e) {
            $io->error('Unable to add the file to media library. ' . $e->getMessage());

            return 1;
        }

        $io->success('File added successfully to media library.');

        return 0;
    }
}
