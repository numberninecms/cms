<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Loader;

use NumberNine\Theme\ThemeStore;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Loader\FilesystemLoader;

final class ThemeTwigLoader extends FilesystemLoader implements EventSubscriberInterface
{
    private ThemeStore $themeStore;

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['load', 4800],
        ];
    }

    public function __construct(ThemeStore $themeStore)
    {
        parent::__construct();
        $this->themeStore = $themeStore;
    }

    public function load(): void
    {
        $theme = $this->themeStore->getCurrentTheme();

        $this->addPath($theme->getTemplatePath(), $theme->getName());
        $this->addPath($theme->getComponentPath(), $theme->getName() . 'Components');

        if ($parent = $theme->getParent()) {
            $this->addPath($parent->getTemplatePath(), $parent->getName());
            $this->addPath($parent->getComponentPath(), $parent->getName() . 'Components');
        }
    }
}
