<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\Menu;
use NumberNine\Event\MenuShortcodeStyleEvent;
use NumberNine\Model\PageBuilder\Control\MenuControl;
use NumberNine\Model\PageBuilder\Control\SelectControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use NumberNine\Repository\ContentEntityRepository;
use NumberNine\Repository\MenuRepository;
use NumberNine\Content\PermalinkGenerator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_depth;

/**
 * @Shortcode(name="menu", label="Menu", icon="mdi-file-tree")
 */
final class MenuShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function __construct(private MenuRepository $menuRepository, private ContentEntityRepository $contentEntityRepository, private PermalinkGenerator $permalinkGenerator, private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        /** @var MenuShortcodeStyleEvent $event */
        $event = $this->eventDispatcher->dispatch(new MenuShortcodeStyleEvent());

        $builder
            ->add('id', MenuControl::class, ['label' => 'Menu'])
            ->add('style', SelectControl::class, ['choices' => $event->getStyles()])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'id' => null,
            'style' => null,
        ]);
    }

    public function processParameters(array $parameters): array
    {
        $menuItems = $this->getMenuItems($parameters);

        return [
            'menuItems' => $menuItems,
            'style' => $parameters['style'],
        ];
    }

    private function getMenuItems(array $parameters): array
    {
        if (!($menu = $this->getMenu($parameters['id']))) {
            return [];
        }

        $entityIds = $this->getEntityIds($menu->getMenuItems());
        $entities = $this->contentEntityRepository->findBy(['id' => $entityIds]);

        return $this->prepareMenuItems($menu->getMenuItems(), $entities);
    }

    private function getMenu(?int $id): ?Menu
    {
        if (!$id) {
            return null;
        }

        return $this->menuRepository->find($id);
    }

    private function getEntityIds(array $menuItems): array
    {
        $ids = [];
        $children = [];

        foreach ($menuItems as $menuItem) {
            if (!empty($menuItem['entityId'])) {
                $ids[] = $menuItem['entityId'];
            }

            if (!empty($menuItem['children'])) {
                $children[] = $this->getEntityIds($menuItem['children']);
            }
        }

        return array_unique(array_merge($ids, ...$children));
    }

    private function prepareMenuItems(array $menuItems, array $entities): array
    {
        return array_map(
            function ($menuItem) use ($entities) {
                if (!empty($menuItem['entityId'])) {
                    $menuItem['url'] = $this->permalinkGenerator->generateContentEntityPermalink(
                        current(array_filter(
                            $entities,
                            static fn (ContentEntity $entity) => $entity->getId() === (int)$menuItem['entityId'],
                        ))
                    );
                }

                if (!empty($menuItem['children'])) {
                    $menuItem['children'] = $this->prepareMenuItems($menuItem['children'], $entities);
                }

                return $menuItem;
            },
            $menuItems
        );
    }
}
