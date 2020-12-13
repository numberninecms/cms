<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\PageBuilder;

use NumberNine\Model\PageBuilder\Control\TextBoxControl;

use function Symfony\Component\String\u;

final class PageBuilderFormBuilder implements PageBuilderFormBuilderInterface
{
    private array $children = [];

    public function add(string $child, ?string $type = null, array $options = []): self
    {
        if ($type === null) {
            $type = TextBoxControl::class;
        }

        if (!is_subclass_of($type, PageBuilderFormControlInterface::class)) {
            throw new \LogicException(sprintf(
                '%s must implement %s',
                $type,
                PageBuilderFormControlInterface::class,
            ));
        }

        if (!array_key_exists('label', $options)) {
            $options['label'] = u($child)->snake()->replace('_', ' ')->title();
        }

        /** @var PageBuilderFormControlInterface $control */
        $control = new $type($options);

        $this->children[$child] = [
            'name' => $type,
            'parameters' => $control->getOptions(),
        ];

        return $this;
    }

    public function all(): array
    {
        return $this->children;
    }
}
