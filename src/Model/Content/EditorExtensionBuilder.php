<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Model\Content;

use NumberNine\Model\PageBuilder\PageBuilderFormBuilder;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;

use function Symfony\Component\String\u;

final class EditorExtensionBuilder implements EditorExtensionBuilderInterface
{
    private array $children = [];

    public function add(string $child, ?string $type = null, array $options = []): self
    {
        if ($type !== null && !in_array($type, [self::COMPONENT_TYPE_TAB, self::COMPONENT_TYPE_SIDEBAR])) {
            throw new \LogicException(
                'Parameter $type must be one of EditorExtensionBuilderInterface::COMPONENT_TYPE_TAB or ' .
                'EditorExtensionBuilderInterface::COMPONENT_TYPE_SIDEBAR'
            );
        }

        if (!array_key_exists('label', $options)) {
            $options['label'] = u($child)->snake()->replace('_', ' ')->title();
        }

        $this->children[$child] = [
            'name' => $child,
            'type' => $type ?? self::COMPONENT_TYPE_TAB,
            'parameters' => $options,
            'builder' => new PageBuilderFormBuilder(),
        ];

        return $this;
    }

    public function getBuilder(string $name): PageBuilderFormBuilderInterface
    {
        if (!array_key_exists($name, $this->children)) {
            throw new \LogicException(sprintf('Child named "%s" doesn\'t exist.', $name));
        }

        return $this->children[$name]['builder'];
    }

    public function all(): array
    {
        $children = [
            'tabs' => [],
            'sidebarComponents' => [],
        ];

        foreach ($this->children as $child) {
            switch ($child['type']) {
                case self::COMPONENT_TYPE_SIDEBAR:
                    $key = 'sidebarComponents';
                    break;

                case self::COMPONENT_TYPE_TAB:
                default:
                    $key = 'tabs';
            }

            $children[$key][] = [
                'name' => $child['name'],
                'parameters' => $child['parameters'],
                'controls' => $child['builder']->all(),
            ];
        }

        return $children;
    }
}
