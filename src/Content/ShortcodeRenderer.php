<?php

namespace NumberNine\Content;

use NumberNine\Theme\TemplateResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function Symfony\Component\String\u;

class ShortcodeRenderer
{
    private Environment $twig;
    private TemplateResolver $templateResolver;
    private ShortcodeStore $shortcodeStore;
    private AuthorizationCheckerInterface $authorizationChecker;
    private TagAwareCacheInterface $cache;

    public function __construct(
        Environment $twig,
        TemplateResolver $templateResolver,
        ShortcodeStore $shortcodeStore,
        AuthorizationCheckerInterface $authorizationChecker,
        TagAwareCacheInterface $cache
    ) {
        $this->twig = $twig;
        $this->templateResolver = $templateResolver;
        $this->shortcodeStore = $shortcodeStore;
        $this->authorizationChecker = $authorizationChecker;
        $this->cache = $cache;
    }

    /**
     * @param string $shortcodeName
     * @param array $args
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderShortcode(string $shortcodeName, array $args = []): string
    {
        $shortcode = $this->shortcodeStore->getShortcode($shortcodeName);

        if ($shortcode) {
            foreach ($args as $property => $value) {
                $setter = 'set' . u($property)->camel()->title();
                if (method_exists($shortcode, $setter)) {
                    $shortcode->$setter($value);
                }
            }

            return $this->twig->render(
                $this->templateResolver->resolveComponent($shortcode),
                $shortcode->getExposedValues()
            );
        }

        if ($this->authorizationChecker->isGranted('Administrator')) {
            return $this->twig->render(
                '@NumberNine/alerts/component_missing.html.twig',
                ['shortcode' => $shortcodeName]
            );
        }

        return '';
    }
}
