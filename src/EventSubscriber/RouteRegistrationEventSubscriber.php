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
    private ContentService $contentService;
    private TaxonomyRepository $taxonomyRepository;
    private SluggerInterface $slugger;
    private ConfigurationReadWriter $configurationReadWriter;

    public function __construct(
        ContentService $contentService,
        TaxonomyRepository $taxonomyRepository,
        SluggerInterface $slugger,
        ConfigurationReadWriter $configurationReadWriter
    ) {
        $this->contentService = $contentService;
        $this->taxonomyRepository = $taxonomyRepository;
        $this->slugger = $slugger;
        $this->configurationReadWriter = $configurationReadWriter;
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
     * @param RouteRegistrationEvent $event
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    public function registerFrontendRoutes(RouteRegistrationEvent $event): void
    {
        $permalinks = $this->configurationReadWriter->read(Settings::PERMALINKS);

        // Homepage route
        $route = new Route('/', ['_controller' => HomepageAction::class], [], [], '', [], ['GET'], '');
        $routePage = new Route('/page/{page<\d+>}/', ['_controller' => HomepageAction::class], [], [], '', [], ['GET'], '');
        $event->addRoute('numbernine_homepage', $route);
        $event->addRoute('numbernine_homepage_page', $routePage);

        // Content entities routes
        foreach ($this->contentService->getContentTypes() as $contentType) {
            $route = (new Route(str_replace('{slug}', '{slug<[\w\-]+>}', $permalinks[$contentType->getName()] ?? $contentType->getPermalink())))
                ->setDefaults(
                    [
                        '_controller' => ContentEntityShowAction::class,
                        '_content_type' => $contentType
                    ]
                )
                ->setMethods(['GET', 'POST']);

            $routeName = sprintf('numbernine_%s_show', $contentType->getName());
            $event->addRoute($routeName, $route);
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
