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

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPageBuilderFormControl implements PageBuilderFormControlInterface
{
    private array $options;

    public function __construct(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $resolver->setRequired('label');
        $resolver->setDefined('default');
        $this->options = $resolver->resolve($options);
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
    }
}
