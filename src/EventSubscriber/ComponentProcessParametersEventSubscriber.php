<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\EventSubscriber;

use NumberNine\Content\DataTransformerProcessor;
use NumberNine\Event\ComponentProcessParametersEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ComponentProcessParametersEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private DataTransformerProcessor $dataTransformerProcessor)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ComponentProcessParametersEvent::class => 'transformValues',
        ];
    }

    public function transformValues(ComponentProcessParametersEvent $event): void
    {
        $values = [];

        foreach ($event->getParameters() as $key => $value) {
            $values[$key] = $this->dataTransformerProcessor->transform($value);
        }

        $event->setParameters($values);
    }
}
