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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\String\Slugger\SluggerInterface;

use function NumberNine\Common\Util\ConfigUtil\file_put_env_variable;

final class DockerInstallCommand extends Command implements ContentTypeAwareCommandInterface
{
    protected static $defaultName = 'numbernine:docker:install';

    private SluggerInterface $slugger;
    private string $projectPath;
    private string $publicPath;

    private OutputInterface $output;
    private SymfonyStyle $io;
    private ?string $appName;
    private bool $reset;
    private string $envFile;
    private int $port = 0;
    private int $verbosity;

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
            ->addOption(
                'reset',
                null,
                InputOption::VALUE_NONE,
                'Remove all uploaded files and all existing migrations'
            )
            ->addOption(
                'app-name',
                null,
                InputOption::VALUE_REQUIRED,
                'Application name'
            )
            ->setDescription('Creates a ready-to-use Docker development environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
        $this->envFile = $this->projectPath . '/.env.local';
        $this->reset = (bool)$input->getOption('reset');
        $this->appName = $input->getOption('app-name'); // @phpstan-ignore-line
        $this->verbosity = $this->io->getVerbosity();

        $tasks = [
            [$this, 'prepareDockerComposeFile'],
            [$this, 'symlinkAdmin'],
            [$this, 'createSSLCertificate'],
            [$this, 'requireRedisBundle'],
            [$this, 'findEmptyPort'],
            [$this, 'createDockerContainers'],
            [$this, 'installDatabase'],
        ];

        $this->createBaseVariables();

        $progressBar = new ProgressBar($output, count($tasks));
        $progressBar->setFormat('normal');

        if ($this->verbosity <= OutputInterface::VERBOSITY_NORMAL) {
            $progressBar->start();
        }

        foreach ($tasks as $task) {
            if ($this->verbosity <= OutputInterface::VERBOSITY_NORMAL) {
                $this->io->setVerbosity(OutputInterface::VERBOSITY_QUIET);
            }

            /** @var callable $task */
            if (($errorCode = call_user_func($task)) !== Command::SUCCESS) {
                $this->io->error('An error occurred. Aborting installation.');
                return $errorCode;
            }

            $this->io->setVerbosity($this->verbosity);

            if ($this->verbosity <= OutputInterface::VERBOSITY_NORMAL) {
                $progressBar->advance();
            }
        }

        if ($this->verbosity <= OutputInterface::VERBOSITY_NORMAL) {
            $progressBar->finish();
        }

        $this->io->newLine(2);
        $this->io->success('NumberNine Docker install complete.');
        $this->io->comment(sprintf(
            'Access your website through <comment>https://localhost%s/</comment>',
            $this->port === 443 ? '' : ':' . $this->port
        ));

        return Command::SUCCESS;
    }

    private function createBaseVariables(): int
    {
        if (!$this->appName) {
            $this->io->title('General settings');

            $this->appName = $this->io->ask('Application name', 'numbernine', function ($appName) {
                if (empty($appName)) {
                    throw new \RuntimeException('Application name cannot be empty.');
                }

                return $this->slugger->slug($appName, '_')->lower();
            });
        }

        if (file_put_env_variable($this->envFile, 'APP_NAME', $this->appName) === false) {
            $this->io->error("Unable to create file '.env.local'");
            return Command::FAILURE;
        }

        file_put_env_variable(
            $this->envFile,
            'DATABASE_URL',
            'mysql://user:user@mysql:3306/numbernine_app?serverVersion=5.7'
        );
        file_put_env_variable($this->envFile, 'REDIS_URL', 'redis://redis:6379');

        file_put_env_variable($this->envFile, 'MAILER_DSN', 'smtp://maildev:25');

        return Command::SUCCESS;
    }

    private function prepareDockerComposeFile(): int
    {
        $finalFilename = "{$this->projectPath}/docker-compose.yml";
        $recipeFilename = "{$this->projectPath}/docker/docker-compose.yaml";

        if (!file_exists($finalFilename)) {
            $result = false;

            if (file_exists($recipeFilename)) {
                $result = rename($recipeFilename, $finalFilename);

                if (!$result) {
                    $this->io->error('Unable to move docker-compose.yml in project directory.');

                    return Command::FAILURE;
                }
            }

            if (!$result) {
                $this->io->error('File docker/docker-compose.yaml not found. Consider reinstalling recipe.');

                return Command::FAILURE;
            }
        }

        $result = false;

        if ($content = file_get_contents($finalFilename)) {
            $result = file_put_contents($finalFilename, str_replace(
                '1000:1000',
                getmyuid() . ':' . getmygid(),
                $content
            ));
        }

        if ($result === false) {
            $this->io->error('File docker-compose.yml not found. Consider reinstalling recipe.');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function symlinkAdmin(): int
    {
        $source = $this->publicPath . '/bundles/numbernine/admin';

        if (!file_exists($source)) {
            $this->io->error('You must call assets:install before calling this command.');
            return Command::FAILURE;
        }

        $filesystem = new Filesystem();
        $filesystem->mirror($source, $this->publicPath . '/admin/');

        return Command::SUCCESS;
    }

    private function createSSLCertificate(): int
    {
        $process = Process::fromShellCommandline('which openssl');
        $exitCode = $process->run();

        if ($exitCode !== Command::SUCCESS) {
            $this->io->error("You must have openssl installed on your host to continue.");
            return Command::FAILURE;
        }

        $certPath = sprintf('%s/docker/nginx/cert', $this->projectPath);

        if (!file_exists($certPath) && !mkdir($certPath, 0755, true)) {
            $this->io->error("Unable to create directory 'docker/nginx/cert'. Please check permissions.");
            return Command::FAILURE;
        }

        $keyFile = sprintf('%s/localhost.key', $certPath);
        $certFile = sprintf('%s/localhost.crt', $certPath);

        if (file_exists($keyFile) && file_exists($certFile)) {
            return Command::SUCCESS;
        }

        $command = sprintf(
            'openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 -subj "%s" -keyout %s -out %s',
            '/C=US/ST=Denial/L=Springfield/O=Dis/CN=localhost',
            $keyFile,
            $certFile,
        );

        $process = Process::fromShellCommandline($command);

        try {
            $this->getHelper('process')->mustRun($this->output, $process);
        } catch (ProcessFailedException $exception) {
            $this->io->error("Unable to create SSL certificate.");
            $this->io->error($exception->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function requireRedisBundle(): int
    {
        $composerBaseCmd = sprintf(
            'docker run --rm --name numbernine_installer %s -u "$(id -u):$(id -g)" ' .
            '-v %s:/srv/app -w /srv/app numberninecms/php:7.4-fpm-dev composer ',
            Process::isTtySupported() ? '-it' : '',
            $this->projectPath
        );

        $composerRequire = Process::fromShellCommandline(
            sprintf(
                $composerBaseCmd . 'require numberninecms/redis --no-scripts%s',
                $this->verbosity <= OutputInterface::VERBOSITY_NORMAL ? ' --quiet' : ''
            )
        )
            ->setTimeout(null)
            ->setTty(Process::isTtySupported());

        $clearCache = Process::fromShellCommandline(
            sprintf(
                'php bin/console cache:clear%s',
                $this->verbosity <= OutputInterface::VERBOSITY_NORMAL ? ' --quiet' : ''
            )
        )
            ->setTimeout(null)
            ->setTty(Process::isTtySupported());

        $composerDumpautoload = Process::fromShellCommandline(
            sprintf(
                $composerBaseCmd . 'dumpautoload%s',
                $this->verbosity <= OutputInterface::VERBOSITY_NORMAL ? ' --quiet' : ''
            )
        )
            ->setTimeout(null)
            ->setTty(Process::isTtySupported());

        try {
            $composerRequire->mustRun();
            $clearCache->mustRun();
            $composerDumpautoload->mustRun();
        } catch (ProcessFailedException $exception) {
            $this->io->error("Unable to install numberninecms/redis package.");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function findEmptyPort(): int
    {
        $ports = [443, ...range(8000, 8100)];
        $context = stream_context_create(
            [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]
        );

        foreach ($ports as $port) {
            try {
                $connection = stream_socket_client(
                    sprintf('ssl://localhost:%d', $port),
                    $errno,
                    $errstr,
                    10,
                    STREAM_CLIENT_CONNECT,
                    $context
                );

                if (is_resource($connection)) {
                    fclose($connection);
                }
            } catch (\Exception $exception) {
                $this->port = $port;
                break;
            }
        }

        if ($this->port === 0) {
            $this->io->error("Unable find an empty port to serve your application.");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function createDockerContainers(): int
    {
        $filename = sprintf('%s/docker-compose.yml', $this->projectPath);
        $dockerCompose = (string)file_get_contents($filename);
        $dockerCompose = str_replace(
            ['${APP_NAME:-numbernine}', '${NGINX_PORT:-443}'],
            [$this->appName, $this->port],
            $dockerCompose
        );
        file_put_contents($filename, $dockerCompose);

        $command = 'docker-compose up -d';

        if ($this->verbosity <= OutputInterface::VERBOSITY_NORMAL) {
            $command = "{ $command --quiet-pull; } > /dev/null 2>&1";
        }

        $process = Process::fromShellCommandline($command)
            ->setTimeout(null)
            ->setTty(Process::isTtySupported());

        try {
            $this->getHelper('process')->mustRun($this->output, $process);
        } catch (ProcessFailedException $exception) {
            $this->io->error("Unable to create Docker containers.");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function installDatabase(): int
    {
        $php = sprintf(
            'docker-compose exec %sphp php',
            Process::isTtySupported() ? '' : '-T ',
        );
        $symfony = "$php bin/console";
        $command = "$php -r 'set_time_limit(30); for(;;) { if(@fsockopen(\"mysql:\".(3306))) { break; } }'";
        $quiet = $this->verbosity <= OutputInterface::VERBOSITY_NORMAL ? ' --quiet' : '';

        try {
            $process = Process::fromShellCommandline("rm -rf var/cache && mkdir -pm 0755 var/cache")
                ->setTimeout(300)
                ->setTty(Process::isTtySupported());
            $this->getHelper('process')->mustRun($this->output, $process);

            $process = Process::fromShellCommandline($command)
                ->setTimeout(30)
                ->setTty(Process::isTtySupported());
            $this->getHelper('process')->mustRun($this->output, $process);

            if ($this->reset) {
                $process = Process::fromShellCommandline('rm -rf migrations/*.php')
                    ->setTty(Process::isTtySupported());
                $this->getHelper('process')->mustRun($this->output, $process);

                $process = Process::fromShellCommandline('rm -rf public/uploads')
                    ->setTty(Process::isTtySupported());
                $this->getHelper('process')->mustRun($this->output, $process);
            }

            $process = Process::fromShellCommandline("$symfony doctrine:database:drop$quiet --if-exists --force")
                ->setTimeout(300)
                ->setTty(Process::isTtySupported());
            $this->getHelper('process')->mustRun($this->output, $process);

            $process = Process::fromShellCommandline("$symfony doctrine:database:create$quiet --if-not-exists")
                ->setTimeout(300)
                ->setTty(Process::isTtySupported());
            $this->getHelper('process')->mustRun($this->output, $process);

            $process = Process::fromShellCommandline("$symfony doctrine:migrations:diff$quiet --no-interaction")
                ->setTimeout(300)
                ->setTty(Process::isTtySupported());
            $this->getHelper('process')->mustRun($this->output, $process);

            $process = Process::fromShellCommandline("$symfony doctrine:migrations:migrate$quiet --no-interaction")
                ->setTimeout(300)
                ->setTty(Process::isTtySupported());
            $this->getHelper('process')->mustRun($this->output, $process);

            $process = Process::fromShellCommandline("$symfony doctrine:fixtures:load$quiet --no-interaction")
                ->setTimeout(null)
                ->setTty(Process::isTtySupported());
            $this->getHelper('process')->mustRun($this->output, $process);

            $process = Process::fromShellCommandline("$symfony cache:clear$quiet")
                ->setTimeout(300)
                ->setTty(Process::isTtySupported());
            $this->getHelper('process')->mustRun($this->output, $process);
        } catch (ProcessFailedException $exception) {
            $this->io->error("Database installation failed.");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
