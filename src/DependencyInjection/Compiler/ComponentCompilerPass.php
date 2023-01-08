<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\DependencyInjection\Compiler;

use NumberNine\Content\ComponentStore;
use NumberNine\Model\Component\AbstractFormComponent;
use NumberNine\Theme\TemplateResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ComponentCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ComponentStore::class)) {
            return;
        }

        $definition = $container->findDefinition(ComponentStore::class);
        $taggedComponents = $container->findTaggedServiceIds('numbernine.component');

        foreach ($taggedComponents as $componentServiceId => $componentTags) {
            $definition->addMethodCall('addComponent', [new Reference($componentServiceId)]);

            $componentDefinition = $container->findDefinition($componentServiceId);

            if (is_subclass_of($componentDefinition->getClass(), AbstractFormComponent::class)) {
                $componentDefinition
                    ->addMethodCall('setEventDispatcher', [new Reference('event_dispatcher')])
                    ->addMethodCall('setTemplateResolver', [new Reference(TemplateResolverInterface::class)])
                    ->addMethodCall('initialize')
                ;
            }
        }
    }
}
