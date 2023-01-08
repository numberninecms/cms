<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use NumberNine\ChapterOne\NumberNineChapterOneBundle;
use NumberNine\NumberNineBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;
use SymfonyCasts\Bundle\ResetPassword\SymfonyCastsResetPasswordBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;

final class NumberNineBundleKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new CmfRoutingBundle(),
            new StofDoctrineExtensionsBundle(),
            new WebpackEncoreBundle(),
            new DoctrineBundle(),
            new DoctrineFixturesBundle(),
            new DoctrineMigrationsBundle(),
            new DAMADoctrineTestBundle(),
            new NumberNineBundle(),
            new NumberNineChapterOneBundle(),
            new SymfonyCastsResetPasswordBundle(),
            new TwigExtraBundle(),
            new MonologBundle(),
        ];
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/../var/cache/test/';
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/app.yaml');
        $container->import('../config/app_test.yaml');
        $container->import('../config/services.yaml');
        $container->import('../config/services_test.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/routing.yaml');
    }
}
