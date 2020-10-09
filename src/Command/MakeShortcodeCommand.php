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
use Symfony\Component\String\Slugger\SluggerInterface;

use function Symfony\Component\String\u;

final class MakeShortcodeCommand extends Command
{
    protected static $defaultName = 'numbernine:make:shortcode';

    private ThemeStore $themeStore;

    public function __construct(ThemeStore $themeStore)
    {
        parent::__construct();
        $this->themeStore = $themeStore;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new shortcode')
            ->setHelp('This command allows you to create a shortcode in the current theme, or in the theme your choose.')
            ->addArgument('shortcode-class-name', InputArgument::OPTIONAL, 'Shortcode class name')
            ->addArgument('shortcode-name', InputArgument::OPTIONAL, 'Shortcode name for the user (e.g. [shortcode-name])')
            ->addOption('editable', null, InputOption::VALUE_OPTIONAL, 'Is the shortcode editable in page builder?')
            ->addOption('container', null, InputOption::VALUE_OPTIONAL, 'Is the shortcode editable in page builder?')
            ->addOption('icon', null, InputOption::VALUE_OPTIONAL, 'Material Design icon for the shortcode editor in page builder (see material.io)')
            ->addOption('theme', null, InputOption::VALUE_OPTIONAL, 'Theme to create shortcode into')
            ->addOption('no-sample-data', null, InputOption::VALUE_NONE, "Don't create sample data");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $shortcodeClassName = $input->getArgument('shortcode-class-name');
        $shortcodeName = $input->getArgument('shortcode-name');
        $editable = $input->getOption('editable');
        $container = $input->getOption('container');
        $icon = $input->getOption('icon');
        /** @var string $themeName */
        $themeName = $input->getOption('theme');
        $noSampleData = $input->getOption('no-sample-data');

        try {
            $theme = !$themeName ? $this->themeStore->getCurrentTheme() : $this->themeStore->getTheme((string)$themeName);
        } catch (ThemeNotFoundException $e) {
            $io->error($e->getMessage());
            return 1;
        }

        if (!$theme instanceof ThemeInterface) {
            $io->error('No theme found. Specify a theme for shortcode creation with --theme option.');
            return 1;
        }

        if (!$shortcodeClassName || !$shortcodeName) {
            $io->title('Create a new shortcode');

            if (!$shortcodeClassName) {
                $shortcodeClassName = $io->ask('Choose a shortcode class name', 'MyShortcode');
            }

            if (!$shortcodeName) {
                $shortcodeName = $io->ask('Choose a shortcode name for the user', u(str_replace('Shortcode', '', $shortcodeClassName))->snake());
            }

            if ($editable === null) {
                $editable = (bool)$io->confirm('Will this shortcode be editable in page builder?', true);
            }

            if ($container === null) {
                $container = (bool)$io->confirm('Can this shortcode contain other shortcodes?', false);
            }

            if ($editable && !$icon) {
                $icon = $io->ask('Choose an icon name (see material.io for full list)', 'create');
            }

            if (!$noSampleData) {
                $sampleData = $io->confirm('Do you want to create sample data?', true);
                $noSampleData = !$sampleData;
            }
        }

        try {
            $reflection = new ReflectionClass($theme);
            $namespace = $reflection->getNamespaceName();
            $path = $theme->getShortcodePath();
            $shortcodePath = $path . $shortcodeClassName . '/';

            if (!mkdir($shortcodePath, 0755, true) && !is_dir($shortcodePath)) {
                throw new RuntimeException("Unable to create directory $shortcodePath.");
            }

            $shortcodeBasename = basename($shortcodeClassName);
            $shortcodeClassFilename = $shortcodeBasename . '.php';

            $annotation = sprintf(
                '@Shortcode(name="%s", label="%s"%s%s%s)',
                $shortcodeName,
                str_replace('Shortcode', '', $shortcodeBasename),
                $editable ? ', editable=true' : '',
                $container ? ', container=true' : '',
                $editable && $icon ? ', icon="' . $icon . '"' : ''
            );

            $sampleData = $noSampleData ? '' : <<<SAMPLE_DATA
    /**
     * @var string
     * @Control\TextBox(label="Sample property")
     */
    public \$sampleProperty = 'Some text';
SAMPLE_DATA;


            file_put_contents(
                $shortcodePath . $shortcodeClassFilename,
                <<<SHORTCODE
<?php

namespace $namespace\\Shortcode\\$shortcodeClassName;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * $annotation
 */
final class $shortcodeBasename extends AbstractShortcode
{
$sampleData
}
SHORTCODE
            );

            file_put_contents($shortcodePath . 'template.html.twig', sprintf('<div>%s</div>', $noSampleData ? '' : '{{ sampleProperty }}'));
            file_put_contents($shortcodePath . 'template.vue.twig', sprintf('{%% verbatim %%}<div>%s</div>{%% endverbatim %%}', $noSampleData ? '' : '{{ parameters.sampleProperty }}'));
        } catch (Exception $e) {
            $io->error('Shortcode cannot be created.');
            $io->writeln($e->getMessage());
            return 1;
        }

        $io->success("Shortcode $shortcodeClassName has been created.");
        $io->writeln("<info>$shortcodePath$shortcodeClassFilename</info>");
        $io->writeln("<info>{$shortcodePath}template.html.twig</info>");
        $io->writeln("<info>{$shortcodePath}template.vue.twig</info>");
        $io->newLine();
        $io->writeln(
            sprintf('Use it either in the page builder either in text editor with: <comment>[%s%s]</comment>', $shortcodeName, $noSampleData ? '' : 'sample_property="Some alternative text"')
        );
        $io->newLine();

        return 0;
    }
}
