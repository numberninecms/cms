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

use NumberNine\Exception\ChildMenuItemNotFoundException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

final class MenuItem
{
    private string $text;
    private ?string $route = null;
    private ?string $link = null;
    private ?string $icon = null;
    private ?string $ifGranted = null;
    private int $position;

    /** @var MenuItem[] */
    private array $children = [];

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        foreach ($options as $key => $value) {
            $property = u($key)->camel()->toString();

            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): void
    {
        $this->route = $route;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    public function getIfGranted(): ?string
    {
        return $this->ifGranted;
    }

    public function setIfGranted(?string $ifGranted): self
    {
        $this->ifGranted = $ifGranted;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function addChild(string $key, self $child): self
    {
        $this->children[$key] = $child;
        $child->setPosition(\count($this->children) * 100);

        return $this;
    }

    public function removeChild(string $key): self
    {
        if (!\array_key_exists($key, $this->children)) {
            throw new ChildMenuItemNotFoundException($this, $key);
        }

        unset($this->children[$key]);

        return $this;
    }

    public function hasChildren(): bool
    {
        return \count($this->children) > 0;
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
    public function setChildren(array $children): self
    {
        $this->children = $children;

        return $this;
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'position' => 0,
        ]);

        $resolver->setDefined(['text', 'route', 'link', 'icon', 'position', 'children', 'if_granted']);
        $resolver->setRequired(['text']);

        $resolver
            ->setAllowedTypes('text', 'string')
            ->setAllowedTypes('route', ['string', 'null'])
            ->setAllowedTypes('link', ['string', 'null'])
            ->setAllowedTypes('icon', ['string', 'null'])
            ->setAllowedTypes('if_granted', ['string', 'null'])
            ->setAllowedTypes('position', 'int')
            ->setAllowedTypes('children', ['array', self::class . '[]'])
        ;

        $resolver->setNormalizer('children', static function (Options $options, array $children): array {
            $newChildren = $children;

            foreach ($children as $key => $child) {
                if (\is_array($child)) {
                    $newChildren[$key] = new self($child);
                }
            }

            $i = 0;
            array_walk($newChildren, function (self $menuItem) use (&$i): void {
                $menuItem->setPosition((($i++) + 1) * 100);
            });

            return $newChildren;
        });
    }
}
