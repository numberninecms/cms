<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\PaginationShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Event\PaginatorEvent;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Pagination\Paginator;

/**
 * @Shortcode(name="pagination", label="Pagination")
 */
final class PaginationShortcode extends AbstractShortcode
{
    private ?Paginator $paginator = null;

    public static function getSubscribedEvents(): array
    {
        return array_merge(
            parent::getSubscribedEvents(),
            [
                PaginatorEvent::class => 'initPaginator'
            ]
        );
    }

    public function initPaginator(PaginatorEvent $event): void
    {
        $this->paginator = new Paginator($event->getPaginator());
    }

    /**
     * @param PaginationShortcodeData $data
     */
    public function process($data): void
    {
        $data->setPaginator($this->paginator);
    }
}
