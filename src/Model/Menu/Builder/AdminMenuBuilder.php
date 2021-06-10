<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Menu\Builder;

use NumberNine\Model\Menu\MenuItem;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AdminMenuBuilder
{
    private AuthorizationCheckerInterface $authorizationChecker;

    /** @var MenuItem[] */
    private array $menuItems = [];

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function reset(): void
    {
        $this->menuItems = [];
    }

    public function append(string $key, array $itemSchema): self
    {
        if ($menuItem = $this->createMenuItemFromSchema($itemSchema)) {
            $this->menuItems[$key] = $menuItem;
        }

        return $this;
    }

    public function insertBefore(string $keyToSearchFor, string $key, array $itemSchema): self
    {
        if (!($menuItem = $this->createMenuItemFromSchema($itemSchema))) {
            return $this;
        }

        $offset = array_search($keyToSearchFor, array_keys($this->menuItems), true);

        $this->menuItems = array_merge(
            (array)array_slice($this->menuItems, 0, (int)$offset, true),
            [$key => $menuItem],
            array_slice($this->menuItems, (int)$offset, null, true),
        );

        return $this;
    }

    /**
     * @param string $keyToSearchFor Can be either a menu name or a path, e.g. 'settings' or 'settings.general'
     */
    public function insertAfter(string $keyToSearchFor, string $key, array $itemSchema): self
    {
        if (!($menuItem = $this->createMenuItemFromSchema($itemSchema))) {
            return $this;
        }

        $tokens = explode('.', $keyToSearchFor);
        $this->insertAfterItem($tokens, $this->menuItems, [$key => $menuItem]);
        return $this;
    }

    /**
     * @param string[] $path
     * @param MenuItem[] $items
     * @param array $menuToInsert
     * @return bool
     */
    private function insertAfterItem(array &$path, array &$items, array $menuToInsert): bool
    {
        $currentPath = (string)array_shift($path);

        if (!array_key_exists($currentPath, $items)) {
            return false;
        }

        if (count($path) > 0) {
            $children = $items[$currentPath]->getChildren();

            if ($this->insertAfterItem($path, $children, $menuToInsert)) {
                $items[$currentPath]->setChildren($children);
                return true;
            }

            return false;
        }

        $offset = array_search($currentPath, array_keys($items), true) + 1;
        $tail = $offset < count($items) ? array_slice($items, $offset, null, true) : null;

        $items = array_merge(
            array_slice($items, 0, $offset, true),
            $menuToInsert
        );

        if ($tail) {
            $items = array_merge($items, $tail);
        }

        return true;
    }

    private function createMenuItemFromSchema(array $itemSchema): ?MenuItem
    {
        if (!empty($itemSchema['if_granted']) && !$this->authorizationChecker->isGranted($itemSchema['if_granted'])) {
            return null;
        }

        $menuItem = new MenuItem(array_merge($itemSchema, ['position' => (count($this->menuItems) + 1) * 100]));

        foreach ($menuItem->getChildren() as $key => $child) {
            if ($child->getIfGranted() && !$this->authorizationChecker->isGranted($child->getIfGranted())) {
                $menuItem->removeChild($key);
            }
        }

        return $menuItem;
    }

    /**
     * @return MenuItem[]
     */
    public function getMenuItems(): array
    {
        return $this->menuItems;
    }
}
