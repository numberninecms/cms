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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

final class InstallCommand extends Command
{
    protected static $defaultName = 'numbernine:install';

    private string $publicPath;

    public function __construct(string $publicPath)
    {
        parent::__construct();
        $this->publicPath = $publicPath;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Installs NumberNine Admin assets');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $source = $this->publicPath . '/bundles/numbernine/admin';

        if (!file_exists($source)) {
            $io->error('You must call assets:install before calling this command.');
            return 1;
        }

        $filesystem = new Filesystem();
        $filesystem->mirror($source, $this->publicPath . '/admin/');

        $io->success('Install complete.');

        return 0;
    }
}
