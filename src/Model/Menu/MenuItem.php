<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Menu;

/**
 * Class MenuItem
 * @package NumberNine\Model\Menu
 * ApiResource
 */
final class MenuItem
{
    /** @var string */
    private $text;

    /** @var string */
    private $route;

    /** @var string */
    private $link;

    /** @var string */
    private $icon;

    /** @var int */
    private $position;

    /** @var MenuItem[] */
    private $children = [];

    /**
     * MenuItem constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->text = $options['text'] ?? '';
        $this->route = $options['route'] ?? '';
        $this->link = $options['link'] ?? '';
        $this->icon = $options['icon'] ?? '';
        $this->position = $options['position'] ?? 0;
    }

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @param string $key
     * @param MenuItem $child
     */
    public function addChild(string $key, MenuItem $child): void
    {
        $this->children[$key] = $child;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    /**
     * @return MenuItem[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param MenuItem[] $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }
}
