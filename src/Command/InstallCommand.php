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

use NumberNine\Command\ContentTypeAwareCommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

final class InstallCommand extends Command implements ContentTypeAwareCommandInterface
{
    protected static $defaultName = 'numbernine:install';

    private string $projectPath;
    private string $publicPath;

    public function __construct(string $projectPath, string $publicPath)
    {
        parent::__construct();
        $this->projectPath = $projectPath;
        $this->publicPath = $publicPath;
    }

    protected function configure(): void
    {
        $this
            ->addOption('force', null, InputOption::VALUE_OPTIONAL, "Force recreation of '.env.local' file")
            ->setDescription('Installs NumberNine Admin assets and setup database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $force = $input->getOption('force');

        $source = $this->publicPath . '/bundles/numbernine/admin';

        if (!file_exists($source)) {
            $io->error('You must call assets:install before calling this command.');
            return Command::FAILURE;
        }

        $filesystem = new Filesystem();
        $filesystem->mirror($source, $this->publicPath . '/admin/');

        $envFile = $this->projectPath . '/.env.local';

        if ($force || !file_exists($envFile)) {
            if (($result = $this->createEnvFile($io, $envFile)) !== Command::SUCCESS) {
                return $result;
            }
        }

        $io->success('Install complete.');

        return Command::SUCCESS;
    }

    private function createEnvFile(SymfonyStyle $io, string $envFile): int
    {
        $io->title('Database configuration wizard');

        $user = $io->ask('Database user', 'root', function ($user) {
            if (empty($user)) {
                throw new \RuntimeException('User cannot be empty.');
            }

            return $user;
        });

        $password = $io->ask('Database password', 'root');

        $name = $io->ask('Database name', 'numbernine', function ($name) {
            if (empty($name)) {
                throw new \RuntimeException('Database name cannot be empty.');
            }

            return $name;
        });

        $host = $io->ask('Host', 'localhost', function ($host) {
            if (empty($host)) {
                throw new \RuntimeException('Host cannot be empty.');
            }

            return $host;
        });

        $port = $io->ask('Port', '3306', function ($port) {
            if (!is_numeric($port)) {
                throw new \RuntimeException('Port must be a number.');
            }

            return (int) $port;
        });

        $url = sprintf('mysql://%s%s@%s:%d/%s?serverVersion=5.7', $user, $password ? ':' . $password : '', $host, $port, $name);

        if (file_put_contents($envFile, sprintf('DATABASE_URL=%s', $url)) === false) {
            $io->error("Unable to create file '.env.local'");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
