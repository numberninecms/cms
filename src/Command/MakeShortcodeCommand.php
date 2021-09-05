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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Twig\Environment;
use function Symfony\Component\String\u;

final class MakeShortcodeCommand extends Command
{
    protected static $defaultName = 'numbernine:make:shortcode';

    public function __construct(
        private Environment $twig,
        private string $shortcodesPath,
        private string $projectPath,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new shortcode')
            ->setHelp(
                'This command allows you to create a shortcode in the current theme, or in the theme your choose.'
            )
            ->addArgument('shortcode-class-name', InputArgument::OPTIONAL, 'Shortcode class name')
            ->addArgument(
                'shortcode-name',
                InputArgument::OPTIONAL,
                'Shortcode name for the end-user (e.g. [shortcode_name])'
            )
            ->addOption('editable', null, InputOption::VALUE_NONE, 'Is the shortcode editable in page builder?')
            ->addOption('container', null, InputOption::VALUE_NONE, 'Is the shortcode editable in page builder?')
            ->addOption(
                'icon',
                null,
                InputOption::VALUE_REQUIRED,
                'Material Design or MDI icon for the shortcode editor in page builder ' .
                '(see material.io or materialdesignicons.com)'
            )
            ->addOption('no-sample-data', null, InputOption::VALUE_NONE, "Don't create sample data");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $shortcodeClassName = $input->getArgument('shortcode-class-name');
        $shortcodeName = $input->getArgument('shortcode-name');
        $editable = (bool)$input->getOption('editable');
        $container = (bool)$input->getOption('container');
        $icon = $input->getOption('icon');

        if (!$shortcodeClassName || !$shortcodeName) {
            $io->title('Create a new shortcode');

            if (!$shortcodeClassName) {
                $shortcodeClassName = $io->ask('Choose a shortcode class name', 'TurtleShortcode');
            }

            if (!$shortcodeName) {
                $shortcodeName = $io->ask(
                    'Choose a shortcode name for the user',
                    u(str_replace('Shortcode', '', $shortcodeClassName))->snake()
                );
            }

            if ($editable === false) {
                $editable = (bool)$io->confirm('Will this shortcode be editable in page builder?', true);
            }

            if ($container === false) {
                $container = (bool)$io->confirm('Can this shortcode contain other shortcodes?', false);
            }

            if ($editable && !$icon) {
                $icon = $io->ask(
                    'Choose an icon name (see material.io or materialdesignicons.com for full list)',
                    'mdi-tortoise'
                );
            }
        }

        try {
            $shortcodeTemplatesPath = $this->shortcodesPath . $shortcodeClassName . '/';
            $relativeShortcodePath = substr($this->shortcodesPath, (int)strpos($this->shortcodesPath, 'src/'));
            $namespace = trim('App\\' . str_replace(
                [$this->projectPath . '/src/', '//', '/'],
                ['', '/', '\\'],
                $this->shortcodesPath,
            ), '\\');

            if (!file_exists($shortcodeTemplatesPath)) {
                if (!mkdir($shortcodeTemplatesPath, 0755, true) && !is_dir($shortcodeTemplatesPath)) {
                    throw new RuntimeException("Unable to create directory $shortcodeTemplatesPath.");
                }
            }

            $shortcodeBasename = basename($shortcodeClassName);
            $shortcodeClassFilename = $shortcodeBasename . '.php';

            $attribute = sprintf(
                "#[Shortcode(name: '%s', label: '%s'%s%s)]",
                $shortcodeName,
                str_replace('Shortcode', '', $shortcodeBasename),
                $container ? ', container: true' : '',
                $editable && $icon ? ", icon: '" . $icon . "'" : ''
            );

            $editableInterface = $editable ? "use NumberNine\Model\Shortcode\EditableShortcodeInterface;\n" : '';
            $implementsEditable = $editable ? ', EditableShortcodeInterface' : '';

            file_put_contents(
                $this->shortcodesPath . $shortcodeClassFilename,
                $this->twig->render('@NumberNine/templates/shortcode.php.twig', compact(
                    'namespace',
                    'attribute',
                    'editableInterface',
                    'implementsEditable',
                ))
            );

            copy(
                __DIR__ . '/../Bundle/Resources/views/templates/shortcode_template.html.twig',
                $shortcodeTemplatesPath . 'template.html.twig'
            );

            copy(
                __DIR__ . '/../Bundle/Resources/views/templates/shortcode_template.vue.twig',
                $shortcodeTemplatesPath . 'template.vue.twig'
            );
        } catch (Exception $e) {
            $io->error('Shortcode cannot be created.');
            $io->writeln($e->getMessage());
            return Command::FAILURE;
        }

        $io->success("Shortcode $shortcodeClassName has been created.");
        $io->writeln("<info>$relativeShortcodePath$shortcodeClassFilename</info>");
        $io->writeln("<info>{$relativeShortcodePath}{$shortcodeClassName}/template.html.twig</info>");
        $io->writeln("<info>{$relativeShortcodePath}{$shortcodeClassName}/template.vue.twig</info>");
        $io->newLine();
        $io->writeln(
            sprintf(
                'Use it either in the page builder either in text editor with: <comment>[%s %s]</comment>',
                $shortcodeName,
                'title="A custom title" age="50"'
            )
        );
        $io->newLine();

        return Command::SUCCESS;
    }
}
