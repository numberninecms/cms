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
    private ?Menu $menu;
    private string $location;

    /**
     * @param Menu $menu
     * @param string $location
     */
    public function __construct(?Menu $menu, string $location)
    {
        $this->menu = $menu;
        $this->location = $location;
    }

    /**
     * @return Menu
     */
    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    /**
     * @param Menu $menu
     */
    public function setMenu(Menu $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
}
