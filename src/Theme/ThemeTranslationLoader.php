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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ThemeTranslationLoader implements EventSubscriberInterface
{
    public function __construct(private TranslatorInterface $translator, private ThemeStore $themeStore)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['loadTranslations', 4600],
        ];
    }

    public function loadTranslations(): void
    {
        $currentTheme = $this->themeStore->getCurrentTheme();
        $this->loadThemeTranslations($currentTheme);
    }

    /**
     * Loads theme translations and merge its parent's translations.
     */
    private function loadThemeTranslations(ThemeInterface $theme, string $translationDomain = null): void
    {
        $translationDomain = $translationDomain ?: $theme->getTranslationDomain();

        // Load parent theme's translations into the child theme's domain
        if ($parent = $theme->getParent()) {
            $this->loadThemeTranslations($parent, $translationDomain);
        }

        // A theme child can override parent's theme translations as they're declared later
        $translations = glob($theme->getTranslationPath() . '*.yaml');

        if (!empty($translations)) {
            foreach ($translations as $translation) {
                if (method_exists($this->translator, 'getFallBackLocales')) {
                    $fallbackLocales = $this->translator->getFallbackLocales();
                }

                if (method_exists($this->translator, 'addResource')) {
                    if (preg_match('/^.*\.(.*)\.yaml$/siU', basename($translation), $matches)) {
                        $locale = $matches[1];
                        $this->translator->addResource('yaml', $translation, $locale, $translationDomain);
                    } elseif (!empty($fallbackLocales)) {
                        $this->translator->addResource('yaml', $translation, $fallbackLocales[0], $translationDomain);
                    }
                }
            }
        }
    }
}
