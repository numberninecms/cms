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
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;
use function NumberNine\Common\Util\ConfigUtil\file_env_variable_exists;
use function NumberNine\Common\Util\ConfigUtil\file_put_env_variable;

final class InstallCommand extends Command implements ContentTypeAwareCommandInterface
{
    protected static $defaultName = 'numbernine:install';
    private SymfonyStyle $io;
    private OutputInterface $output;

    public function __construct(private SluggerInterface $slugger, private string $projectPath, private string $publicPath)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force recreation of mandatory environment variables')
            ->addOption('sub-commands-only', null, InputOption::VALUE_NONE, 'Run sub-commands only')
            ->setDescription('Installs NumberNine Admin assets and setup database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->output = $output;
        $force = $input->getOption('force');
        $subCommands = $input->getOption('sub-commands-only');

        if ($subCommands) {
            if (($result = $this->runSubCommands()) !== Command::SUCCESS) {
                return $result;
            }

            $this->io->success('NumberNine install complete.');

            return Command::SUCCESS;
        }

        $envFile = $this->projectPath . '/.env.local';

        if ($force || !file_env_variable_exists($envFile, 'APP_NAME')) {
            if (($result = $this->createBaseVariables($envFile)) !== Command::SUCCESS) {
                return $result;
            }
        }

        if ($force || !file_env_variable_exists($envFile, 'DATABASE_URL')) {
            if (($result = $this->createDatabaseString($envFile)) !== Command::SUCCESS) {
                return $result;
            }
        }

        if ($force || !file_env_variable_exists($envFile, 'REDIS_URL')) {
            if (($result = $this->installRedis()) !== Command::SUCCESS) {
                return $result;
            }
        }

        $this->io->comment('NumberNine pre-install complete.');

        return Command::SUCCESS;
    }

    private function createBaseVariables(string $envFile): int
    {
        $this->io->title('General settings');

        $appName = $this->io->ask(
            'Application name',
            'numbernine',
            function ($appName): AbstractUnicodeString {
                if (empty($appName)) {
                    throw new RuntimeException('Application name cannot be empty.');
                }

                return $this->slugger->slug($appName, '_')->lower();
            }
        );

        if (file_put_env_variable($envFile, 'APP_NAME', $appName) === false) {
            $this->io->error("Unable to create file '.env.local'");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function createDatabaseString(string $envFile): int
    {
        $this->io->title('MySQL settings');

        $user = $this->io->ask('Database user', 'root', function ($user) {
            if (empty($user)) {
                throw new RuntimeException('User cannot be empty.');
            }

            return $user;
        });

        $password = $this->io->ask('Database password', 'root');

        $name = $this->io->ask('Database name', 'numbernine', function ($name) {
            if (empty($name)) {
                throw new RuntimeException('Database name cannot be empty.');
            }

            return $name;
        });

        $host = $this->io->ask('Host', 'localhost', function ($host) {
            if (empty($host)) {
                throw new RuntimeException('Host cannot be empty.');
            }

            return $host;
        });

        $port = $this->io->ask('Port', '3306', function ($port): int {
            if (!is_numeric($port)) {
                throw new RuntimeException('Port must be a number.');
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
            $this->io->error("Unable to create file '.env.local'");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function installRedis(): int
    {
        if (!$this->io->confirm('Do you want to use Redis?')) {
            return Command::SUCCESS;
        }

        $this->io->writeln('Installing required composer package...');

        $composerProcess = (new Process(['composer', 'require', 'numberninecms/redis']))
            ->setTty(Process::isTtySupported())
        ;
        $composerProcess->run(function (string $type, string $buffer): void {
            $this->io->write($buffer);
        });

        if (!$composerProcess->isSuccessful()) {
            throw new ProcessFailedException($composerProcess);
        }

        return Command::SUCCESS;
    }

    private function runSubCommands(): int
    {
        $listSubCommandsProcess = Process::fromShellCommandline(
            'php bin/console list numbernine:install 2>/dev/null'
        );

        try {
            $listSubCommandsProcess->mustRun();
            $exitCode = $listSubCommandsProcess->getExitCode();

            if ($exitCode !== Command::SUCCESS) {
                return Command::SUCCESS;
            }
        } catch (Exception) {
            return Command::SUCCESS;
        }

        $result = $listSubCommandsProcess->getOutput();

        if (!preg_match_all('/(numbernine:install:[\w\d-]+)/', $result, $matches)) {
            $this->io->error('Unable to find NumberNine sub-commands even though they exist.');

            return Command::FAILURE;
        }

        $commands = $matches[1];

        /** @var Application $application */
        $application = $this->getApplication();

        foreach ($commands as $commandName) {
            $command = $application->find($commandName);
            if (($exitCode = $command->run(new ArrayInput([]), $this->output)) !== Command::SUCCESS) {
                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }
}
