<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Content;

use NumberNine\Model\Component\ComponentInterface;
use NumberNine\Theme\ThemeStore;

final class ComponentStore
{
    private string $appComponentsNamespace;

    /** @var ComponentInterface[] */
    private array $components = [];

    public function __construct(private ThemeStore $themeStore, string $projectPath, string $componentsPath)
    {
        $this->appComponentsNamespace = trim('App\\' . str_replace(
            [$projectPath . '/src/', '//', '/'],
            ['', '/', '\\'],
            $componentsPath,
        ), '\\');
    }

    public function addComponent(ComponentInterface $component): void
    {
        $this->components[$component::class] = $component;
    }

    public function getComponent(string $componentName): ?ComponentInterface
    {
        $theme = $this->themeStore->getCurrentTheme();

        foreach ($this->components as $componentFqcn => $component) {
            if (
                trim(\dirname(str_replace(
                    [$theme->getComponentNamespace(), $this->appComponentsNamespace, '\\'],
                    ['', '', '/'],
                    $componentFqcn
                )), '/') === str_replace('\\', '/', $componentName)
            ) {
                return $component;
            }
        }

        return null;
    }
}
