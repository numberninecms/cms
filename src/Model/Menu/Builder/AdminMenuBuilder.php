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

use NumberNine\Exception\MenuItemNotFoundException;
use NumberNine\Model\Menu\MenuItem;

final class AdminMenuBuilder
{
    /** @var MenuItem[] */
    private array $menuItems = [];

    public function reset(): void
    {
        $this->menuItems = [];
    }

    public function append(string $key, array $itemSchema): self
    {
        $this->menuItems[$key] = $this->createMenuItemFromSchema($itemSchema);

        return $this;
    }

    public function insertBefore(string $keyToSearchFor, string $key, array $itemSchema): self
    {
        $menuItem = $this->createMenuItemFromSchema($itemSchema);

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
        $menuItem = $this->createMenuItemFromSchema($itemSchema);

        $tokens = explode('.', $keyToSearchFor);
        $this->insertAfterItem($tokens, $this->menuItems, [$key => $menuItem]);
        return $this;
    }

    /**
     * @param string[] $path
     * @param MenuItem[] $items
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

    private function createMenuItemFromSchema(array $itemSchema): MenuItem
    {
        return new MenuItem(array_merge($itemSchema, ['position' => (count($this->menuItems) + 1) * 100]));
    }

    /**
     * @return MenuItem[]
     */
    public function getMenuItems(): array
    {
        return $this->menuItems;
    }

    public function removeMenuItem(string $key): self
    {
        if (!array_key_exists($key, $this->menuItems)) {
            throw new MenuItemNotFoundException($key);
        }

        unset($this->menuItems[$key]);

        return $this;
    }
}
