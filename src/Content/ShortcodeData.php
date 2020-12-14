<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Content;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\EventDispatcher\Event;

use function Symfony\Component\String\u;

abstract class ShortcodeData extends Event
{
    public function __construct(array $parameters = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined(array_keys($parameters));
        $this->configureShortcodeParameters($resolver);
        $parameters = $resolver->resolve($parameters);

        foreach ($parameters as $key => $value) {
            $property = u($key)->camel()->toString();

            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    abstract protected function configureShortcodeParameters(OptionsResolver $resolver): void;
    abstract public function getTemplateParameters(): array;
}
