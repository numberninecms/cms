<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Theme;

use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Model\Theme\ThemeWrapper;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ThemeStore implements EventSubscriberInterface
{
    private ThemeWrapper $currentTheme;

    /** @var ThemeWrapper[] */
    private array $themeWrappers = [];

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleCommandEvent::class => ['loadThemeWrappers', 5000],
            RequestEvent::class => ['loadThemeWrappers', 5000],
        ];
    }

    public function __construct(private ThemeMetadataFactory $themeMetadataFactory, private iterable $themes)
    {
    }

    public function loadThemeWrappers(): void
    {
        foreach ($this->themes as $theme) {
            $descriptor = $this->themeMetadataFactory->getThemeDescriptor($theme);
            $theme->setName($descriptor->getName());
            $theme->setSlug($descriptor->getSlug());
            $this->themeWrappers[$theme::class] = new ThemeWrapper($descriptor, $theme);
        }
    }

    /**
     * @return ThemeWrapper[]
     */
    public function getThemes(): array
    {
        return $this->themeWrappers;
    }

    public function getTheme(string $themeNameOrClassName): ?ThemeWrapper
    {
        if (is_a($themeNameOrClassName, ThemeWrapper::class)) {
            return $this->themeWrappers[$themeNameOrClassName] ?? null;
        }

        return current(array_filter(
            $this->themeWrappers,
            fn(ThemeWrapper $wrapper) => $wrapper->getDescriptor()->getName() === $themeNameOrClassName
        )) ?: null;
    }

    public function getCurrentTheme(): ThemeInterface
    {
        return $this->currentTheme->getTheme();
    }

    public function setCurrentTheme(ThemeWrapper $currentTheme): void
    {
        $this->currentTheme = $currentTheme;
    }

    public function getCurrentThemeName(): string
    {
        return $this->currentTheme->getDescriptor()->getName();
    }
}
