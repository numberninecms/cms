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

use NumberNine\Event\Theme\FooterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Profiler\Profiler;

final class FooterEventSubscriber implements EventSubscriberInterface
{
    private ?Request $request;
    private ?Profiler $profiler;

    /**
     * @param RequestStack $requestStack
     * @param Profiler|null $profiler
     */
    public function __construct(RequestStack $requestStack, ?Profiler $profiler)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->profiler = $profiler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FooterEvent::class => [
                ['hideProfiler', 512]
            ],
        ];
    }

    /**
     * Hides profiler for customizer
     */
    public function hideProfiler(): void
    {
        if ($this->profiler && $this->request && $this->request->get('n9') === 'admin') {
            $this->profiler->disable();
        }
    }
}
