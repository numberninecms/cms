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
use NumberNine\Content\ComponentGenerator;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MakeComponentCommand extends Command
{
    protected static $defaultName = 'numbernine:make:component';

    public function __construct(
        private ComponentGenerator $componentGenerator,
        private string $componentsPath,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new component')
            ->setHelp(
                'This command allows you to create a component in the current theme, or in the theme your choose.'
            )
            ->addArgument('component-name', InputArgument::OPTIONAL, 'Component name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $componentName = $input->getArgument('component-name');

        if (empty($componentName)) {
            $io->title('Create a new component');

            if (!$componentName) {
                $componentName = $io->ask('Choose a component name', 'Testimonials');
            }
        }

        $path = $this->componentsPath;
        $componentPath = $path . $componentName . '/';
        $relativeComponentPath = substr($componentPath, (int) strpos($componentPath, 'src/'));

        $componentBasename = basename($componentName);
        $componentClassFilename = $componentBasename . '.php';

        try {
            if (!file_exists($componentPath) && !mkdir($componentPath, 0755, true) && !is_dir($componentPath)) {
                throw new RuntimeException("Unable to create directory {$componentPath}.");
            }

            $result = $this->componentGenerator->generate($componentName);
            file_put_contents($componentPath . $componentClassFilename, $result['class']);
            file_put_contents($componentPath . 'template.html.twig', $result['template']);
        } catch (Exception $e) {
            $io->error('Component cannot be created.');
            $io->writeln($e->getMessage());

            return Command::FAILURE;
        }

        $io->success("Component {$componentName} has been created.");
        $io->writeln("<info>{$relativeComponentPath}{$componentClassFilename}</info>");
        $io->writeln("<info>{$relativeComponentPath}template.html.twig</info>");
        $io->newLine();
        $componentName = str_replace('\\', '/', $componentName);
        $io->writeln('Use it in your template with the following twig command: ' .
            "<comment>{{ N9_component('{$componentName}') }}</comment>");
        $io->newLine();

        return Command::SUCCESS;
    }
}
