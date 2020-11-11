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

use NumberNine\Model\Shortcode\CacheableContent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function Symfony\Component\String\u;

final class ComponentRenderer
{
    private Environment $twig;
    private ComponentStore $componentStore;
    private AuthorizationCheckerInterface $authorizationChecker;
    private TagAwareCacheInterface $cache;

    public function __construct(
        Environment $twig,
        ComponentStore $componentStore,
        AuthorizationCheckerInterface $authorizationChecker,
        TagAwareCacheInterface $cache
    ) {
        $this->twig = $twig;
        $this->componentStore = $componentStore;
        $this->authorizationChecker = $authorizationChecker;
        $this->cache = $cache;
    }

    /**
     * @param string $componentName
     * @param array $args
     * @return string
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
                    $component->$setter($value);
                }
            }

            if (is_subclass_of($component, CacheableContent::class)) {
                return $this->cache->get(
                    $component->getCacheIdentifier(),
                    function () use ($component) {
                        return $component->render();
                    }
                );
            } else {
                return $component->render();
            }
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
