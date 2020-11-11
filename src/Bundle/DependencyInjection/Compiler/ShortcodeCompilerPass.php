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

use NumberNine\Content\ShortcodeStore;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ShortcodeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ShortcodeStore::class) === false) {
            return;
        }

        $definition = $container->findDefinition(ShortcodeStore::class);
        $taggedShortcodes = $container->findTaggedServiceIds('numbernine.shortcode');

        foreach ($taggedShortcodes as $serviceId => $taggedShortcode) {
            $definition->addMethodCall('addShortcode', [new Reference($serviceId)]);
        }
    }
}
