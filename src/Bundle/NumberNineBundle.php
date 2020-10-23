<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Bundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use NumberNine\Bundle\DependencyInjection\Compiler\ComponentCompilerPass;
use NumberNine\Bundle\DependencyInjection\Compiler\ShortcodeCompilerPass;
use NumberNine\Content\RenderableInspectorInterface;
use NumberNine\Model\Bundle\Bundle;
use NumberNine\Model\Component\ComponentInterface;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Model\DataTransformer\DataTransformerInterface;
use NumberNine\Content\RenderableInspector;
use NumberNine\Security\Capability\CapabilityInterface;
use NumberNine\Theme\TemplateResolver;
use NumberNine\Theme\ThemeToolbox;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class NumberNineBundle extends Bundle
{
    protected function getAlias(): string
    {
        return 'numbernine';
    }

    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(CapabilityInterface::class)->addTag('numbernine.security.capability');
        $container->registerForAutoconfiguration(DataTransformerInterface::class)->addTag('numbernine.data_transformer');
        $container->registerForAutoconfiguration(ThemeInterface::class)->addTag('numbernine.theme');
        $container->registerForAutoconfiguration(ComponentInterface::class)->addTag('numbernine.component')
            ->addMethodCall('setTwig', [new Reference('twig')])
            ->addMethodCall('setToolbox', [new Reference(ThemeToolbox::class)])
            ->addMethodCall('setRenderableInspector', [new Reference(RenderableInspector::class)])
            ->addMethodCall('setEventDispatcher', [new Reference(EventDispatcherInterface::class)]);
        $container->registerForAutoconfiguration(ShortcodeInterface::class)->addTag('numbernine.shortcode')
            ->addMethodCall('setTwig', [new Reference('twig')])
            ->addMethodCall('setEventDispatcher', [new Reference(EventDispatcherInterface::class)])
            ->addMethodCall('setRenderableInspector', [new Reference(RenderableInspectorInterface::class)])
            ->addMethodCall('setTemplateResolver', [new Reference(TemplateResolver::class)]);

        $container->addCompilerPass(new ComponentCompilerPass());
        $container->addCompilerPass(new ShortcodeCompilerPass());
        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createAnnotationMappingDriver(
                [
                    'NumberNine\\Entity',
                    'NumberNine\\Model'
                ],
                [
                    __DIR__ . '/../Entity',
                    __DIR__ . '/../Model'
                ]
            )
        );
    }
}
