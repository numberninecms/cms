<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('numbernine');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('components_path')->defaultValue('src/Component/')->end()
            ->scalarNode('shortcodes_path')->defaultValue('src/Shortcode/')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
