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
use NumberNine\Event\ShortcodeProcessParametersEvent;
use NumberNine\Exception\InvalidShortcodeException;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Shortcode\TextShortcode;
use NumberNine\Theme\TemplateResolver;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Thunder\Shortcode\Parser\ParserInterface;
use Thunder\Shortcode\Shortcode\ParsedShortcodeInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShortcodeRenderer
{
    public function __construct(private EventDispatcherInterface $eventDispatcher, private Environment $twig, private TemplateResolver $templateResolver, private ShortcodeStore $shortcodeStore, private ShortcodeProcessor $shortcodeProcessor, private ParserInterface $shortcodeParser, private TagAwareCacheInterface $cache)
    {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function renderShortcode(ParsedShortcodeInterface $parsedShortcode): string
    {
        try {
            $shortcode = $this->shortcodeStore->getShortcode($parsedShortcode->getName());
        } catch (InvalidShortcodeException) {
            return $parsedShortcode->getText();
        }

        $parameters = $parsedShortcode->getParameters();

        if (!$shortcode instanceof TextShortcode && trim((string)$parsedShortcode->getContent())) {
            $parameters['content'] = $this->applyShortcodes((string)$parsedShortcode->getContent());
        } else {
            $parameters['content'] = trim((string)$parsedShortcode->getContent());
        }

        $resolver = new OptionsResolver();
        $resolver->setDefined(array_keys($parameters)); // @phpstan-ignore-line
        $shortcode->configureParameters($resolver);
        $parameters = $resolver->resolve($parameters);
        $processedParameters = $shortcode->processParameters($parameters);

        /** @var ShortcodeProcessParametersEvent $event */
        $event = $this->eventDispatcher->dispatch(
            new ShortcodeProcessParametersEvent($shortcode, $processedParameters)
        );
        $processedParameters = $event->getParameters();

        return $this->twig->render(
            $this->templateResolver->resolveShortcode($shortcode),
            $processedParameters,
        );
    }

    public function renderPageBuilderTemplate(ShortcodeInterface $shortcode): string
    {
        $template = $this->templateResolver->resolveShortcodePageBuilder($shortcode);

        $template = $this->twig->createTemplate(sprintf(
            '{%% apply spaceless %%}%s{%% endapply %%}',
            !str_contains($template->getTemplateName(), 'string template')
                ? file_get_contents($template->getSourceContext()->getPath())
                : $template->getSourceContext()->getCode(),
        ));

        return $this->twig->render($template);
    }
}
