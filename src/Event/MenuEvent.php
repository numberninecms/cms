<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event;

use NumberNine\Entity\Menu;
use Symfony\Contracts\EventDispatcher\Event;

final class MenuEvent extends Event
{
    /**
     * @param Menu $menu
     */
    public function __construct(private ?Menu $menu, private string $location)
    {
    }

    /**
     * @return Menu
     */
    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(Menu $menu): void
    {
        $this->menu = $menu;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
}
