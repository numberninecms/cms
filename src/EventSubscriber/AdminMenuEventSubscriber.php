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

use NumberNine\Event\AdminMenuEvent;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Content\ContentType;
use NumberNine\Model\Menu\Builder\AdminMenuBuilder;
use NumberNine\Model\Translation\QuickTranslate;
use NumberNine\Repository\TaxonomyRepository;
use NumberNine\Content\ContentService;
use NumberNine\Admin\AdminMenuBuilderStore;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AdminMenuEventSubscriber implements EventSubscriberInterface
{
    use QuickTranslate;

    private TranslatorInterface $translator;
    private EventDispatcherInterface $eventDispatcher;
    private UrlGeneratorInterface $urlGenerator;
    private ContentService $contentService;
    private AdminMenuBuilderStore $adminMenuBuilderStore;
    private SluggerInterface $slugger;
    private TaxonomyRepository $taxonomyRepository;
    private EnglishInflector $inflector;
    private string $configFile;

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => ['initializeCoreMenus', 96],
            AdminMenuEvent::class => 'insertContentEntities'
        ];
    }

    public function __construct(
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher,
        UrlGeneratorInterface $urlGenerator,
        ContentService $contentService,
        AdminMenuBuilderStore $adminMenuBuilderStore,
        SluggerInterface $slugger,
        TaxonomyRepository $taxonomyRepository,
        string $adminMenuConfigPath
    ) {
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
        $this->urlGenerator = $urlGenerator;
        $this->contentService = $contentService;
        $this->adminMenuBuilderStore = $adminMenuBuilderStore;
        $this->slugger = $slugger;
        $this->taxonomyRepository = $taxonomyRepository;
        $this->inflector = new EnglishInflector();
        $this->configFile = __DIR__ . '/../Bundle/Resources/config/' . $adminMenuConfigPath . '/menus.yaml';
    }

    public function initializeCoreMenus(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!$controller instanceof AdminController) {
            return;
        }

        if (!$this->configFile) {
            return;
        }

        $config = Yaml::parseFile($this->configFile);
        $menus = $config['menus'] ?? [];

        $builder = new AdminMenuBuilder();

        foreach ($menus as $key => $menuItem) {
            if (empty($menuItem['link'])) {
                $menuItem['link'] = !empty($menuItem['route'])
                    ? $this->urlGenerator->generate($menuItem['route'])
                    : '/';
            }

            $builder->append($key, $menuItem);
        }

        /** @var AdminMenuEvent $adminMenuEvent */
        $adminMenuEvent = $this->eventDispatcher->dispatch(new AdminMenuEvent($builder));
        $this->adminMenuBuilderStore->setAdminMenuBuilder($adminMenuEvent->getBuilder());
    }

    public function insertContentEntities(AdminMenuEvent $event): void
    {
        $builder = $event->getBuilder();

        foreach ($this->contentService->getContentTypes() as $contentType) {
            if (!$contentType->isShownInMenu()) {
                continue;
            }

            $taxonomiesMenus = $this->getContentTypeTaxonomiesMenus($contentType);

            $linkIndex = $this->urlGenerator->generate('numbernine_admin_content_entity_index', [
                'type' => $this->slugger->slug((string)$contentType->getLabels()->getPluralName())
            ]);

            $linkNew = $this->urlGenerator->generate('numbernine_admin_content_entity_create', [
                'type' => $this->slugger->slug((string)$contentType->getLabels()->getPluralName())
            ]);

            $builder->insertAfter(
                'media_library',
                $contentType->getName() . '_entity',
                [
                    'text' => $contentType->getLabels()->getMenuName(),
                    'link' => $linkIndex,
                    'icon' => $contentType->getIcon(),
                    'children' => array_merge(
                        [
                            'all' => [
                                'text' => $this->__($contentType->getLabels()->getAllItems()),
                                'link' => $linkIndex,
                            ],
                            'add_new' => [
                                'text' => $this->__($contentType->getLabels()->getAddNew()),
                                'link' => $linkNew,
                            ]
                        ],
                        $taxonomiesMenus
                    ),
                ]
            );
        }
    }

    private function getContentTypeTaxonomiesMenus(ContentType $contentType): array
    {
        $taxonomies = $this->taxonomyRepository->findByContentType($contentType);
        $menus = [];

        foreach ($taxonomies as $taxonomy) {
            $name = $taxonomy->getName();
            $plural = (string)current($this->inflector->pluralize((string)$name));

            $menus[$name . '_taxonomy'] = [
                'text' => $this->__(ucfirst($plural)),
                'link' => sprintf('/taxonomy/%s/', $name)
            ];
        }

        return $menus;
    }
}
