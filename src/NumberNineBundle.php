<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use NumberNine\Common\Bundle\BundleTrait;
use NumberNine\DependencyInjection\Compiler\ComponentCompilerPass;
use NumberNine\DependencyInjection\Compiler\ShortcodeCompilerPass;
use NumberNine\Model\Component\ComponentInterface;
use NumberNine\Model\DataTransformer\DataTransformerInterface;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Security\Capability\CapabilityInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class NumberNineBundle extends Bundle
{
    use BundleTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(CapabilityInterface::class)
            ->addTag('numbernine.security.capability')
        ;
        $container->registerForAutoconfiguration(DataTransformerInterface::class)
            ->addTag('numbernine.data_transformer')
        ;
        $container->registerForAutoconfiguration(ThemeInterface::class)->addTag('numbernine.theme');
        $container->registerForAutoconfiguration(ComponentInterface::class)->addTag('numbernine.component');
        $container->registerForAutoconfiguration(ShortcodeInterface::class)->addTag('numbernine.shortcode');

        $container->addCompilerPass(new ComponentCompilerPass());
        $container->addCompilerPass(new ShortcodeCompilerPass());
        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createAttributeMappingDriver(['NumberNine\\Entity'], [__DIR__ . '/Entity'])
        );
    }

    protected function getAlias(): string
    {
        return 'numbernine';
    }
}
