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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @Shortcode(name="pagination", label="Pagination")
 */
final class PaginationShortcode extends AbstractShortcode implements EventSubscriberInterface
{
    private ?Paginator $paginator = null;

    public static function getSubscribedEvents(): array
    {
        return [
            PaginatorEvent::class => 'initPaginator'
        ];
    }

    public function initPaginator(PaginatorEvent $event): void
    {
        $this->paginator = new Paginator($event->getPaginator());
    }

    public function getPaginator(): ?Paginator
    {
        return $this->paginator;
    }
}
