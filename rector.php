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

use PHPStan\Type\Accessory\AccessoryLiteralStringType;
use Rector\Php80\Rector\Class_\DoctrineAnnotationClassToAttributeRector;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;
use Symfony\Component\Routing\Annotation\Route;
use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
    ]);
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, getcwd() . '/phpstan.neon');
    $parameters->set(Option::SKIP, [
        __DIR__ . '/src/Bundle/Resources/public',
        __DIR__ . '/src/Entity/ContentEntity.php',
        ReturnNeverTypeRector::class,
    ]);
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $containerConfigurator->import(SetList::PHP_80);
    $containerConfigurator->import(SetList::PHP_81);
//    $containerConfigurator->import(SetList::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);

    $services = $containerConfigurator->services();

    $services->set(TypedPropertyRector::class)
        ->call('configure', [[
            TypedPropertyRector::CLASS_LIKE_TYPE_ONLY => false,
        ]]);

    $services->set(AnnotationToAttributeRector::class)
        ->call('configure', [[
            AnnotationToAttributeRector::ANNOTATION_TO_ATTRIBUTE => ValueObjectInliner::inline([
                new AnnotationToAttribute(Route::class, null),
            ]),
        ]]);

    $services->set(DoctrineAnnotationClassToAttributeRector::class)
        ->call('configure', [[
            DoctrineAnnotationClassToAttributeRector::REMOVE_ANNOTATIONS => false,
        ]]);

    $services->set(NameImportingPostRector::class);
};
