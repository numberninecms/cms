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

use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $containerConfigurator->import(SetList::PHP_CS_FIXER);
    $containerConfigurator->import(SetList::PHP_CS_FIXER_RISKY);
    $containerConfigurator->import(SetList::DOCTRINE_ANNOTATIONS);

    $services = $containerConfigurator->services();

    $services->set(YodaStyleFixer::class)
        ->call('configure', [[
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ]]);

    $services->set(PhpdocTypesOrderFixer::class)
        ->call('configure', [[
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ]]);

    $services->set(OrderedImportsFixer::class)
        ->call('configure', [[
            'imports_order' => ['class', 'function', 'const'],
        ]]);

    $services->set(ConcatSpaceFixer::class)
        ->call('configure', [[
            'spacing' => 'one',
        ]]);
};
