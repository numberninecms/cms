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

use Exception;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Shortcode\DividerShortcode\DividerShortcode;
use NumberNine\Shortcode\PaginationShortcode\PaginationShortcodeData;
use NumberNine\Shortcode\SectionShortcode\SectionShortcode;
use NumberNine\Shortcode\TextShortcode\TextShortcode;
use NumberNine\Theme\TemplateResolver;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Thunder\Shortcode\Parser\ParserInterface;
use Thunder\Shortcode\Shortcode\ParsedShortcodeInterface;
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
    private ShortcodeProcessor $shortcodeProcessor;
    private ParserInterface $shortcodeParser;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        Environment $twig,
        TemplateResolver $templateResolver,
        ShortcodeStore $shortcodeStore,
        AuthorizationCheckerInterface $authorizationChecker,
        ShortcodeProcessor $shortcodeProcessor,
        ParserInterface $parser,
        TagAwareCacheInterface $cache
    ) {
        $this->twig = $twig;
        $this->templateResolver = $templateResolver;
        $this->shortcodeStore = $shortcodeStore;
        $this->authorizationChecker = $authorizationChecker;
        $this->shortcodeProcessor = $shortcodeProcessor;
        $this->shortcodeParser = $parser;
        $this->cache = $cache;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $text
     * @return string
     * @throws InvalidArgumentException
     */
    public function applyShortcodes(string $text): string
    {
        $text = $this->shortcodeProcessor->insertTextShortcodes($text);

        /** @var ParsedShortcodeInterface[] $parsedShortcodes */
        $parsedShortcodes = $this->cache->get(md5($text), fn () => $this->shortcodeParser->parse($text));

        $renderedText = '';

        foreach ($parsedShortcodes as $parsedShortcode) {
            $renderedText .= $this->renderShortcode($parsedShortcode);
        }

        return $renderedText;
    }

    /**
     * @param ParsedShortcodeInterface $parsedShortcode
     * @return string
     * @throws InvalidArgumentException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function renderShortcode(ParsedShortcodeInterface $parsedShortcode): string
    {
        $shortcode = $this->shortcodeStore->getShortcode($parsedShortcode->getName());
        $parameters = $parsedShortcode->getParameters();

        if (!$shortcode instanceof TextShortcode && trim((string)$parsedShortcode->getContent())) {
            $parameters['content'] = $this->applyShortcodes((string)$parsedShortcode->getContent());
        } else {
            $parameters['content'] = trim((string)$parsedShortcode->getContent());
        }

        if (!$shortcode instanceof TextShortcode && $parameters['content']) {
            $parameters['content'] = $this->applyShortcodes((string)$parsedShortcode->getContent());
        }

        $className = sprintf('%sData', get_class($shortcode));
        $data = null;

        if (class_exists($className) && is_subclass_of($className, ShortcodeData::class)) {
            /** @var ShortcodeData $data */
            $data = $this->eventDispatcher->dispatch(new $className($parameters));
        }

        return $this->twig->render(
            $this->templateResolver->resolveShortcode($shortcode),
            $data ? $data->toArray() : [],
        );
    }
}
