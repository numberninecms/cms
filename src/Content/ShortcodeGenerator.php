<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Content;

use JetBrains\PhpStorm\ArrayShape;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use NumberNine\Attribute\Shortcode;
use NumberNine\Model\PageBuilder\Control\SliderControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ShortcodeGenerator
{
    private PsrPrinter $printer;

    public function __construct(
        private string $shortcodesPath,
        private string $projectPath,
    ) {
        $this->printer = new PsrPrinter();
    }

    #[ArrayShape([
        'class' => 'string',
        'template_html' => 'string',
        'template_vue' => 'string',
    ])]
    public function generate(
        string $shortcodeName,
        #[ArrayShape([
            'name' => 'string',
            'label' => 'string',
            'container' => 'boolean',
            'editable' => 'boolean',
            'icon' => 'string',
        ])] array $options,
    ): array {
        return [
            'class' => $this->generateClass(basename($shortcodeName), $options),
            'template_html' => $this->generateHtmlTemplate(),
            'template_vue' => $this->generateVueTemplate(),
        ];
    }

    private function generateClass(
        string $shortcodeBasename,
        #[ArrayShape([
            'name' => 'string',
            'label' => 'string',
            'container' => 'boolean',
            'editable' => 'boolean',
            'icon' => 'string',
        ])] array $options,
    ): string {
        $namespaceName = trim('App\\' . str_replace(
            [$this->projectPath . '/src/', '//', '/'],
            ['', '/', '\\'],
            $this->shortcodesPath,
        ), '\\');

        $file = (new PhpFile())
            ->setStrictTypes()
        ;

        $namespace = $file->addNamespace($namespaceName)
            ->addUse(Shortcode::class)
            ->addUse(SliderControl::class)
            ->addUse(PageBuilderFormBuilderInterface::class)
            ->addUse(ShortcodeInterface::class)
            ->addUse(OptionsResolver::class)
        ;

        $class = (new ClassType($shortcodeBasename))
            ->setFinal()
            ->addImplement(ShortcodeInterface::class)
        ;

        $attributeArguments = [
            'name' => $options['name'],
            'label' => $options['label'],
        ];

        if (!empty($options['container']) ) {
            $attributeArguments['container'] = true;
        }

        if (!empty($options['editable']) && !empty($options['icon'])) {
            $attributeArguments['icon'] = $options['icon'];
        }

        $class->addAttribute(Shortcode::class, $attributeArguments);

        if (!empty($options['editable'])) {
            $namespace->addUse(EditableShortcodeInterface::class);
            $class->addImplement(EditableShortcodeInterface::class);
        }

        $method = $class->addMethod('buildPageBuilderForm')
            ->setReturnType('void')
            ->addBody('$builder')
            ->addBody("    ->add('title')")
            ->addBody("    ->add('age', SliderControl::class)")
            ->addBody(';')
        ;

        $method->addParameter('builder')
            ->setType(PageBuilderFormBuilderInterface::class)
        ;

        $method = $class->addMethod('configureParameters')
            ->setReturnType('void')
            ->addBody('$resolver->setDefaults([')
            ->addBody("    'title' => 'Welcome to the Turtle Age Show',")
            ->addBody("    'age' => 40,")
            ->addBody(']);')
        ;

        $method->addParameter('resolver')
            ->setType(OptionsResolver::class)
        ;

        $method = $class->addMethod('processParameters')
            ->setReturnType('array')
            ->addBody('return [')
            ->addBody("    'title' => \$parameters['title'],")
            ->addBody("    'age' => \$parameters['age'],")
            ->addBody('];')
        ;

        $method->addParameter('parameters')
            ->setType('array')
        ;

        $namespace->add($class);

        return $this->printer->printFile($file);
    }

    private function generateHtmlTemplate(): string
    {
        return file_get_contents(__DIR__ . '/../Bundle/Resources/views/templates/shortcode_template.html.twig');
    }

    private function generateVueTemplate(): string
    {
        return file_get_contents(__DIR__ . '/../Bundle/Resources/views/templates/shortcode_template.vue.twig');
    }
}
