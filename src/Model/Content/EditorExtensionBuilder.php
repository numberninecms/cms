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

use LogicException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

final class EditorExtensionBuilder implements EditorExtensionBuilderInterface
{
    private array $children = [];

    public function add(string $child, ?string $formType = null, array $options = [], ?string $type = null): self
    {
        if ($type !== null && !\in_array($type, [self::COMPONENT_TYPE_TAB, self::COMPONENT_TYPE_SIDEBAR], true)) {
            throw new LogicException(
                'Parameter $type must be one of EditorExtensionBuilderInterface::COMPONENT_TYPE_TAB or ' .
                'EditorExtensionBuilderInterface::COMPONENT_TYPE_SIDEBAR'
            );
        }

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver, $child);
        $options = $resolver->resolve($options);

        $label = $options['label'] ?? u($child)->snake()->replace('_', ' ')->title()->toString();
        $icon = $options['icon'] ?? 'mdi-tab';
        unset($options['label'], $options['icon']);

        $this->children[$child] = [
            'name' => $child,
            'label' => $label,
            'icon' => $icon,
            'type' => $type ?? self::COMPONENT_TYPE_TAB,
            'options' => $options,
            'form_type' => $formType,
        ];

        return $this;
    }

    public function all(): array
    {
        $children = [
            'tabs' => [],
            'sidebarComponents' => [],
        ];

        foreach ($this->children as $child) {
            $key = match ($child['type']) {
                self::COMPONENT_TYPE_SIDEBAR => 'sidebarComponents',
                default => 'tabs',
            };

            unset($child['type']);
            $children[$key][] = $child;
        }

        return $children;
    }

    private function configureOptions(OptionsResolver $resolver, string $child): void
    {
        $resolver->setDefaults([
            'icon' => 'mdi-tab',
            'label' => null,
        ]);

        $resolver
            ->setAllowedTypes('icon', ['string', 'null'])
            ->setAllowedTypes('label', ['string', 'null'])
        ;
    }
}
