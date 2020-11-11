<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\EventSubscriber;

use Exception;
use NumberNine\Event\Theme\HeaderScriptsEvent;
use NumberNine\Asset\TagRenderer;
use NumberNine\Event\Theme\HeadEvent;
use NumberNine\Event\Theme\HeadStylesheetsEvent;
use NumberNine\Event\Theme\HeadThemeCustomStylesEvent;
use NumberNine\Event\Theme\HeadTitleEvent;
use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Model\General\Settings;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Http\RequestAnalyzer;
use NumberNine\Theme\ThemeOptionsReadWriter;
use NumberNine\Theme\ThemeStore;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class HeadEventSubscriber implements EventSubscriberInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private TagRenderer $tagRenderer;
    private AuthorizationCheckerInterface $authorizationChecker;
    private Environment $twig;
    private ThemeStore $themeStore;
    private ConfigurationReadWriter $configurationReadWriter;
    private ThemeOptionsReadWriter $themeOptionsReadWriter;
    private RequestAnalyzer $requestAnalyzer;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TagRenderer $tagRenderer,
        AuthorizationCheckerInterface $authorizationChecker,
        Environment $twig,
        ThemeStore $themeStore,
        ConfigurationReadWriter $configurationReadWriter,
        ThemeOptionsReadWriter $themeOptionsReadWriter,
        RequestAnalyzer $requestAnalyzer
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->tagRenderer = $tagRenderer;
        $this->authorizationChecker = $authorizationChecker;
        $this->twig = $twig;
        $this->themeStore = $themeStore;
        $this->configurationReadWriter = $configurationReadWriter;
        $this->themeOptionsReadWriter = $themeOptionsReadWriter;
        $this->requestAnalyzer = $requestAnalyzer;
    }

    public static function getSubscribedEvents()
    {
        return [
            HeadEvent::class => [
                ['title', 2048],
                ['stylesheets', 1024],
                ['themeCustomStyles', 512],
                ['scripts', 256],
            ]
        ];
    }

    public function title(HeadEvent $event): void
    {
        $titleEvent = $this->eventDispatcher->dispatch(
            new HeadTitleEvent($this->configurationReadWriter->read(Settings::SITE_TITLE))
        );
        $event->setObject($event . $titleEvent);
    }

    public function stylesheets(HeadEvent $event): void
    {
        $stylesheets = $this->tagRenderer->renderWebpackLinkTags();

        $stylesheetsEvent = $this->eventDispatcher->dispatch(new HeadStylesheetsEvent($stylesheets));
        $event->setObject($event . $stylesheetsEvent);
    }

    /**
     * @param HeadEvent $event
     * @throws ThemeNotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function themeCustomStyles(HeadEvent $event): void
    {
        $theme = $this->themeStore->getCurrentTheme();
        try {
            $styles = $this->twig->render(
                sprintf('@%s/customizable_styles.css.twig', $theme->getName()),
                $this->themeOptionsReadWriter->readAll($theme, false, $this->requestAnalyzer->isPreviewMode())
            );
        } catch (LoaderError $e) {
            $styles = '';
        }
        $stylesEvent = $this->eventDispatcher->dispatch(new HeadThemeCustomStylesEvent($styles));
        $event->setObject($event . sprintf('<style type="text/css">%s</style>', (string)$stylesEvent));
    }

    /**
     * Renders page scripts
     * @param HeadEvent $event
     * @throws Exception
     */
    public function scripts(HeadEvent $event): void
    {
        $scripts = $this->tagRenderer->renderWebpackScriptTags();
        $scriptsEvent = $this->eventDispatcher->dispatch(new HeaderScriptsEvent($scripts));
        $event->setObject($event . $scriptsEvent);
    }
}
