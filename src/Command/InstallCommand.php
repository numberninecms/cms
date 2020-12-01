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

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\String\Slugger\SluggerInterface;

use function NumberNine\Common\Util\ConfigUtil\file_env_variable_exists;
use function NumberNine\Common\Util\ConfigUtil\file_put_env_variable;

final class InstallCommand extends Command implements ContentTypeAwareCommandInterface
{
    protected static $defaultName = 'numbernine:install';

    private SluggerInterface $slugger;
    private string $projectPath;
    private string $publicPath;

    public function __construct(SluggerInterface $slugger, string $projectPath, string $publicPath)
    {
        parent::__construct();
        $this->slugger = $slugger;
        $this->projectPath = $projectPath;
        $this->publicPath = $publicPath;
    }

    protected function configure(): void
    {
        $this
            ->addOption('force', null, InputOption::VALUE_NONE, "Force recreation of mandatory environment variables")
            ->addOption('sub-commands-only', null, InputOption::VALUE_NONE, "Run sub-commands only")
            ->setDescription('Installs NumberNine Admin assets and setup database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $force = $input->getOption('force');
        $subCommands = $input->getOption('sub-commands-only');

        if ($subCommands) {
            if (($result = $this->runSubCommands($output)) !== Command::SUCCESS) {
                return $result;
            }

            $io->success('NumberNine install complete.');

            return Command::SUCCESS;
        }

        $source = $this->publicPath . '/bundles/numbernine/admin';

        if (!file_exists($source)) {
            $io->error('You must call assets:install before calling this command.');
            return Command::FAILURE;
        }

        $filesystem = new Filesystem();
        $filesystem->mirror($source, $this->publicPath . '/admin/');

        $envFile = $this->projectPath . '/.env.local';

        if ($force || !file_env_variable_exists($envFile, 'APP_NAME')) {
            if (($result = $this->createBaseVariables($io, $envFile)) !== Command::SUCCESS) {
                return $result;
            }
        }

        if ($force || !file_env_variable_exists($envFile, 'DATABASE_URL')) {
            if (($result = $this->createDatabaseString($io, $envFile)) !== Command::SUCCESS) {
                return $result;
            }
        }

        if ($force || !file_env_variable_exists($envFile, 'REDIS_URL')) {
            if (($result = $this->installRedis($io)) !== Command::SUCCESS) {
                return $result;
            }
        }

        $io->comment('NumberNine pre-install complete.');

        return Command::SUCCESS;
    }

    private function createBaseVariables(SymfonyStyle $io, string $envFile): int
    {
        $io->title('General settings');

        $appName = $io->ask('Application name', 'numbernine', function ($appName) {
            if (empty($appName)) {
                throw new \RuntimeException('Application name cannot be empty.');
            }

            return $this->slugger->slug($appName, '_')->lower();
        });

        if (file_put_env_variable($envFile, 'APP_NAME', $appName) === false) {
            $io->error("Unable to create file '.env.local'");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function createDatabaseString(SymfonyStyle $io, string $envFile): int
    {
        $io->title('MySQL settings');

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

        $url = sprintf(
            'mysql://%s%s@%s:%d/%s?serverVersion=5.7',
            $user,
            $password ? ':' . $password : '',
            $host,
            $port,
            $name
        );

        if (file_put_env_variable($envFile, 'DATABASE_URL', $url) === false) {
            $io->error("Unable to create file '.env.local'");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function installRedis(SymfonyStyle $io): int
    {
        if (!$io->confirm('Do you want to use Redis?')) {
            return Command::SUCCESS;
        }

        $io->writeln('Installing required composer package...');

        $composerProcess = (new Process(['composer', 'require', 'numberninecms/redis']))
            ->setTty(Process::isTtySupported());
        $composerProcess->run(function (string $type, string $buffer) use ($io) {
            $io->write($buffer);
        });

        if (!$composerProcess->isSuccessful()) {
            throw new ProcessFailedException($composerProcess);
        }

        return Command::SUCCESS;
    }

    private function runSubCommands(OutputInterface $output): int
    {
        $listSubCommandsProcess = Process::fromShellCommandline(
            "php bin/console list numbernine:install | awk '/numbernine:install:/ {print $1}'"
        );
        $listSubCommandsProcess->run();

        $commands = explode("\n", $listSubCommandsProcess->getOutput());

        /** @var Application $application */
        $application = $this->getApplication();

        foreach (array_filter($commands) as $commandName) {
            $command = $application->find($commandName);
            if (($returnCode = $command->run(new ArrayInput([]), $output)) !== Command::SUCCESS) {
                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }
}
