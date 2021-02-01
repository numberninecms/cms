<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Loader;

use NumberNine\Content\ComponentStore;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Loader\FilesystemLoader;

final class ComponentTwigLoader extends FilesystemLoader implements EventSubscriberInterface
{
    private ComponentStore $componentStore;
    private string $componentsPath;

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['load', 4700],
        ];
    }

    public function __construct(ComponentStore $componentStore, string $componentsPath)
    {
        parent::__construct();
        $this->componentStore = $componentStore;
        $this->componentsPath = $componentsPath;
    }

    public function load(): void
    {
        $this->addPath($this->componentsPath, 'AppComponents');
    }
}
