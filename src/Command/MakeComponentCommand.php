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
use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Theme\ThemeStore;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MakeComponentCommand extends Command
{
    protected static $defaultName = 'numbernine:make:component';

    private ThemeStore $themeStore;

    public function __construct(ThemeStore $themeStore)
    {
        parent::__construct();
        $this->themeStore = $themeStore;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new component')
            ->setHelp('This command allows you to create a component in the current theme, or in the theme your choose.')
            ->addArgument('component-name', InputArgument::OPTIONAL, 'Component name')
            ->addOption('theme', 't', InputOption::VALUE_OPTIONAL, 'Theme to create component into');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $componentName = $input->getArgument('component-name');
        /** @var string $themeName */
        $themeName = $input->getOption('theme');

        try {
            $theme = !$themeName ? $this->themeStore->getCurrentTheme() : $this->themeStore->getTheme((string)$themeName);
        } catch (ThemeNotFoundException $e) {
            $io->error($e->getMessage());
            return 1;
        }

        if (!$theme instanceof ThemeInterface) {
            $io->error('No theme found. Specify a theme for component creation with --theme option.');
            return 1;
        }

        if (!($componentName)) {
            $io->title('Create a new component');

            if (!$componentName) {
                $componentName = $io->ask('Choose a component name', 'FeaturedProducts');
            }
        }

        try {
            $reflection = new ReflectionClass($theme);
            $namespace = $reflection->getNamespaceName();
            $path = $theme->getComponentPath();
            $componentPath = $path . $componentName . '/';

            if (!mkdir($componentPath, 0755, true) && !is_dir($componentPath)) {
                throw new RuntimeException("Unable to create directory $componentPath.");
            }

            $componentBasename = basename($componentName);
            $componentClassFilename = $componentBasename . '.php';

            file_put_contents(
                $componentPath . $componentClassFilename,
                <<<COMPONENT
<?php

namespace $namespace\\Component\\$componentName;

use NumberNine\\Model\\Component\\AbstractComponent;

final class $componentBasename extends AbstractComponent
{
}
COMPONENT
            );

            file_put_contents(
                $componentPath . 'template.html.twig',
                <<<TEMPLATE
<p>I'm a component. My name is $componentBasename.</p>
TEMPLATE
            );
        } catch (Exception $e) {
            $io->error('Component cannot be created.');
            $io->writeln($e->getMessage());

            return 1;
        }

        $io->success("Component $componentName has been created.");
        $io->writeln("<info>$componentPath$componentClassFilename</info>");
        $io->writeln("<info>{$componentPath}template.html.twig</info>");
        $io->newLine();
        $componentName = str_replace('\\', '/', $componentName);
        $io->writeln("Use it in your template with the following twig command: <comment>{{ N9_component('$componentName') }}</comment>");
        $io->newLine();

        return 0;
    }
}
