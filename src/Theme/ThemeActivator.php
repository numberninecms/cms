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

use NumberNine\Command\ThemeAwareCommandInterface;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Exception\NoThemeFoundException;
use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Model\General\Settings;
use NumberNine\Repository\ThemeOptionsRepository;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ThemeActivator implements EventSubscriberInterface
{
    private ThemeStore $themeStore;
    private ConfigurationReadWriter $configurationReadWriter;
    private ThemeOptionsRepository $themeOptionsRepository;
    private EventDispatcherInterface $eventDispatcher;

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleCommandEvent::class => ['activateCurrentTheme', 4900],
            RequestEvent::class => ['activateCurrentTheme', 4900],
        ];
    }

    public function __construct(
        ThemeStore $themeStore,
        ConfigurationReadWriter $configurationReadWriter,
        ThemeOptionsRepository $themeOptionsRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->themeStore = $themeStore;
        $this->configurationReadWriter = $configurationReadWriter;
        $this->themeOptionsRepository = $themeOptionsRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ConsoleCommandEvent|RequestEvent $event
     */
    public function activateCurrentTheme($event): void
    {
        if ($event instanceof ConsoleCommandEvent && !$event->getCommand() instanceof ThemeAwareCommandInterface) {
            return;
        }

        if (empty($this->themeStore->getThemes())) {
            throw new NoThemeFoundException();
        }

        /** @var string $currentThemeName */
        $currentThemeName = $this->configurationReadWriter->read(Settings::ACTIVE_THEME);

        if ($currentThemeName) {
            $themeWrapper = $this->themeStore->getTheme($currentThemeName);

            if (!$themeWrapper) {
                throw new ThemeNotFoundException($currentThemeName);
            }
        } else {
            $themeWrapper = current($this->themeStore->getThemes());
            $this->configurationReadWriter->write(Settings::ACTIVE_THEME, $themeWrapper->getDescriptor()->getName());
        }

        $themeOptions = $this->themeOptionsRepository->getOrCreateByThemeName((string)$currentThemeName);
        $themeWrapper->getTheme()->setThemeOptions($themeOptions);

        $themeWrapper->getTheme()->setConfiguration(
            [
                'main_entry' => $themeWrapper->getDescriptor()->getMainEntry(),
                'areas' => $themeWrapper->getDescriptor()->getAreas(),
            ]
        );

        $this->themeStore->setCurrentTheme($themeWrapper);
    }
}
