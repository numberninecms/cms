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

use NumberNine\Controller\Frontend\Content\HomepageAction;
use NumberNine\Controller\Frontend\Content\ContentEntityShowAction;
use NumberNine\Controller\Frontend\Term\IndexAction as TermIndexAction;
use NumberNine\Event\RouteRegistrationEvent;
use NumberNine\Model\General\Settings;
use NumberNine\Repository\TaxonomyRepository;
use NumberNine\Content\ContentService;
use NumberNine\Configuration\ConfigurationReadWriter;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

use function Symfony\Component\String\u;

final class RouteRegistrationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private ContentService $contentService, private TaxonomyRepository $taxonomyRepository, private SluggerInterface $slugger, private ConfigurationReadWriter $configurationReadWriter)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RouteRegistrationEvent::class => [
                ['registerFrontendRoutes', 50],
            ]
        ];
    }

    /**
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    public function registerFrontendRoutes(RouteRegistrationEvent $event): void
    {
        $permalinks = $this->configurationReadWriter->read(Settings::PERMALINKS);

        // Homepage route
        $route = new Route('/', ['_controller' => HomepageAction::class, 'page' => 1], [], [], '', [], ['GET'], '');
        $routePage = new Route(
            '/page/{page<\d+>}/',
            ['_controller' => HomepageAction::class, 'page' => 1],
            [],
            [],
            '',
            [],
            ['GET'],
            ''
        );
        $event->addRoute('numbernine_homepage', $route);
        $event->addRoute('numbernine_homepage_page', $routePage);

        // Content entities routes
        foreach ($this->contentService->getContentTypes() as $contentType) {
            $route = (new Route(str_replace(
                '{slug}',
                '{slug<[\w\-]+>}',
                $permalinks[$contentType->getName()] ?? $contentType->getPermalink()
            )))
                ->setDefaults(
                    [
                        '_controller' => ContentEntityShowAction::class,
                        '_content_type' => $contentType,
                        'page' => 1,
                    ]
                )
                ->setMethods(['GET', 'POST']);

            $routeName = sprintf('numbernine_%s_show', $contentType->getName());
            $event->addRoute($routeName, $route);

            $routePage = clone $route;
            $routePage->setPath($route->getPath() . '/page/{page<\d+>}/');
            $event->addRoute($routeName . '_page', $routePage);
        }

        // Taxonomies routes
        foreach ($this->taxonomyRepository->findAll() as $taxonomy) {
            $slugDashed = $this->slugger->slug((string)$taxonomy->getName());
            $slugUnderscore = $this->slugger->slug((string)$taxonomy->getName(), '_');

            $route = (new Route($slugDashed . '/{slug}/'))
                ->setDefaults(
                    [
                        '_controller' => TermIndexAction::class
                    ]
                )
                ->setMethods(['GET']);

            $routeName = sprintf('numbernine_taxonomy_%s_term_index', $slugUnderscore);
            $event->addRoute($routeName, $route);
        }
    }
}
