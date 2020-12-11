<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\MenuShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\Menu;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Repository\ContentEntityRepository;
use NumberNine\Repository\MenuRepository;
use NumberNine\Content\PermalinkGenerator;

use function NumberNine\Common\Util\ArrayUtil\array_depth;

/**
 * @Shortcode(name="menu", label="Menu", editable=true, icon="menu")
 */
final class MenuShortcode extends AbstractShortcode
{
    private MenuRepository $menuRepository;
    private ContentEntityRepository $contentEntityRepository;
    private PermalinkGenerator $permalinkGenerator;

    private ?Menu $menu = null;

    /**
     * @Exclude("serialization")
     */
    private ?array $menuItems = null;

    public function __construct(
        MenuRepository $menuRepository,
        ContentEntityRepository $contentEntityRepository,
        PermalinkGenerator $permalinkGenerator
    ) {
        $this->menuRepository = $menuRepository;
        $this->contentEntityRepository = $contentEntityRepository;
        $this->permalinkGenerator = $permalinkGenerator;
    }

    public function getMenuItems(MenuShortcodeData $data): array
    {
        if (!($menu = $this->getMenu($data->getId()))) {
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
                    $menuItem['link'] = $this->permalinkGenerator->generateContentEntityPermalink(
                        current(array_filter(
                            $entities,
                            fn(ContentEntity $entity) => $entity->getId() === (int)$menuItem['entityId']
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

    /**
     * @param MenuShortcodeData $data
     */
    public function process($data): void
    {
        $data->setMenuItems($this->getMenuItems($data));
    }
}
