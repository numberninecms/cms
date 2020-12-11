<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Shortcode;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractShortcode implements ShortcodeInterface, EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        $className = sprintf('%sData', static::class);

        if (class_exists($className)) {
            return [
                $className => 'process',
            ];
        }

        return [];
    }

    /**
     * @param mixed $data
     */
    public function process($data): void
    {
    }
}
