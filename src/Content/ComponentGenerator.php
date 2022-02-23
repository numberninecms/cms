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
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use NumberNine\Model\Component\ComponentInterface;

final class ComponentGenerator
{
    private PsrPrinter $printer;

    public function __construct(
        private string $componentsPath,
        private string $projectPath,
    ) {
        $this->printer = new PsrPrinter();
    }

    #[ArrayShape([
        'class' => 'string',
        'template' => 'string',
    ])]
    public function generate(string $componentName): array
    {
        return [
            'class' => $this->generateClass(basename($componentName), $this->componentsPath . $componentName . '/'),
            'template' => $this->generateTemplate(),
        ];
    }

    private function generateClass(string $componentBasename, string $componentPath): string
    {
        $namespaceName = trim('App\\' . str_replace(
            [$this->projectPath . '/src/', '//', '/'],
            ['', '/', '\\'],
            $componentPath,
        ), '\\');

        $class = (new ClassType($componentBasename))
            ->setFinal()
            ->addImplement(ComponentInterface::class)
        ;

        $class->addMethod('getTemplateParameters')
            ->setReturnType('array')
            ->addBody('return [')
            ->addBody("    'name' => ?,", [$componentBasename])
            ->addBody('];')
        ;

        $file = (new PhpFile())
            ->setStrictTypes()
        ;

        $namespace = (new PhpNamespace($namespaceName))
            ->addUse(ComponentInterface::class)
            ->add($class)
        ;

        $file->addNamespace($namespace);

        return $this->printer->printFile($file);
    }

    private function generateTemplate(): string
    {
        return "<p>I'm a component. My name is {{ name }}.</p>";
    }
}
