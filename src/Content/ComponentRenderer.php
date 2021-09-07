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

use NumberNine\Event\ComponentProcessParametersEvent;
use NumberNine\Theme\TemplateResolver;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function Symfony\Component\String\u;

final class ComponentRenderer
{
    public function __construct(private Environment $twig, private TemplateResolver $templateResolver, private ComponentStore $componentStore, private AuthorizationCheckerInterface $authorizationChecker, private TagAwareCacheInterface $cache, private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderComponent(string $componentName, array $args = []): string
    {
        $component = $this->componentStore->getComponent($componentName);

        if ($component) {
            foreach ($args as $property => $value) {
                $setter = 'set' . u($property)->camel()->title();
                if (method_exists($component, $setter)) {
                    $component->{$setter}($value);
                }
            }

            $parameters = $component->getTemplateParameters();
            /** @var ComponentProcessParametersEvent $event */
            $event = $this->eventDispatcher->dispatch(new ComponentProcessParametersEvent($component, $parameters));
            $parameters = $event->getParameters();

            return $this->twig->render($this->templateResolver->resolveComponent($component), $parameters);
        }

        if ($this->authorizationChecker->isGranted('Administrator')) {
            return $this->twig->render(
                '@NumberNine/alerts/component_missing.html.twig',
                ['component' => $componentName]
            );
        }

        return '';
    }
}
