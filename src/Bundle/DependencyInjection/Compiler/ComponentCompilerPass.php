<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Bundle\DependencyInjection\Compiler;

use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Content\ComponentStore;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ComponentCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ComponentStore::class) === false) {
            return;
        }

        $definition = $container->findDefinition(ComponentStore::class);
        $taggedComponents = $container->findTaggedServiceIds('numbernine.component');

        foreach ($taggedComponents as $componentServiceId => $componentTags) {
            $componentClass = new ReflectionClass((string)$componentServiceId);

            if (!$componentClass->implementsInterface(ShortcodeInterface::class)) {
                $definition->addMethodCall('addComponent', [new Reference($componentServiceId)]);
            }
        }
    }
}
