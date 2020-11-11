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
use ReflectionClass;
use ReflectionException;

final class ComponentStore
{
    private ThemeStore $themeStore;

    /** @var ComponentInterface[] */
    private array $components = [];

    public function __construct(ThemeStore $themeStore)
    {
        $this->themeStore = $themeStore;
    }

    /**
     * Only current theme components are loaded
     *
     * @param ComponentInterface $component
     * @throws ReflectionException
     */
    public function addComponent(ComponentInterface $component): void
    {
        $reflection = new ReflectionClass($component);
        $currentThemeNamespace = $this->themeStore->getCurrentTheme()->getNamespace();
        $isCurrentThemeComponent = strpos($reflection->getNamespaceName(), $currentThemeNamespace) !== false;

        if (!$isCurrentThemeComponent) {
            return;
        }

        $relativePath = str_replace(
            [$this->themeStore->getCurrentTheme()->getComponentPath(), '\\'],
            ['', '/'],
            dirname((string)$reflection->getFileName())
        );

        $twigPath = sprintf('@%sComponents/%s', $this->themeStore->getCurrentThemeName(), $relativePath);

        $component->setTemplateName($twigPath . '/template.html.twig');

        $this->components[get_class($component)] = $component;
    }

    /**
     * @param string $componentName
     * @return ComponentInterface|null
     */
    public function getComponent(string $componentName): ?ComponentInterface
    {
        $theme = $this->themeStore->getCurrentTheme();

        foreach ($this->components as $componentFqcn => $component) {
            if (
                trim(dirname(str_replace(
                    [$theme->getComponentNamespace(), '\\'],
                    ['', '/'],
                    $componentFqcn
                )), '/') === str_replace('\\', '/', $componentName)
            ) {
                return $component;
            }
        }

        return null;
    }
}
