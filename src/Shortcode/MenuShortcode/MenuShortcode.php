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

    /**
     * @Control\Menu(label="Menu")
     */
    private ?int $id = null;

    private ?Menu $menu = null;

    /**
     * @Exclude("serialization")
     */
    private ?array $menuItems = null;

    public function __construct(MenuRepository $menuRepository, ContentEntityRepository $contentEntityRepository, PermalinkGenerator $permalinkGenerator)
    {
        $this->menuRepository = $menuRepository;
        $this->contentEntityRepository = $contentEntityRepository;
        $this->permalinkGenerator = $permalinkGenerator;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getMenuItems(): array
    {
        if (!($menu = $this->getMenu())) {
            return [];
        }

        if (!$this->menuItems) {
            $entityIds = $this->getEntityIds($menu->getMenuItems());
            $entities = $this->contentEntityRepository->findBy(['id' => $entityIds]);

            $this->menuItems = $this->prepareMenuItems($menu->getMenuItems(), $entities);
        }

        return $this->menuItems;
    }

    /**
     * @Exclude("serialization")
     */
    public function getDepth(): int
    {
        return array_depth($this->getMenuItems());
    }

    private function getMenu(): ?Menu
    {
        if (!$this->getId()) {
            return null;
        }

        if (!$this->menu) {
            $this->menu = $this->menuRepository->find($this->id);
        }

        return $this->menu;
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
                        current(array_filter($entities, fn(ContentEntity $entity) => $entity->getId() === (int)$menuItem['entityId']))
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
