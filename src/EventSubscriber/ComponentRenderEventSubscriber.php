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

use NumberNine\Event\RenderEvent;
use NumberNine\Content\DataTransformerProcessor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ComponentRenderEventSubscriber implements EventSubscriberInterface
{
    private DataTransformerProcessor $dataTransformerProcessor;

    public function __construct(DataTransformerProcessor $dataTransformerProcessor)
    {
        $this->dataTransformerProcessor = $dataTransformerProcessor;
    }

    public static function getSubscribedEvents()
    {
        return [
            RenderEvent::class => 'transformValues'
        ];
    }

    public function transformValues(RenderEvent $event): void
    {
        $values = [];

        foreach ($event->getValues() as $key => $value) {
            $values[$key] = $this->dataTransformerProcessor->transform($value);
        }

        $event->setValues($values);
    }
}
