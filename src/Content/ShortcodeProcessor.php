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
use NumberNine\Annotation\ExtendedReader;
use NumberNine\Annotation\Form\Responsive;
use NumberNine\Entity\Preset;
use NumberNine\Exception\InvalidShortcodeException;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Repository\PresetRepository;
use NumberNine\Shortcode\TextShortcode\TextShortcode;
use NumberNine\Theme\PresetFinderInterface;
use ReflectionException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Thunder\Shortcode\Parser\ParserInterface;
use Symfony\Component\Uid\Uuid;
use Thunder\Shortcode\Shortcode\ParsedShortcodeInterface;

final class ShortcodeProcessor
{
    private const REGEX_ISOLATE_FULL_SHORTCODE = '@\[(\[?)(%shortcode%)(?![\w\-])([^\]/]*(?:/(?!\])[^\]/]*)*?)' .
        '(?:(/)\]|\](?:([^\[]*(?:\[(?!/\2\])[^\[]*)*)(\[/\2\]))?)(\]?)@';
    private const REGEX_ISOLATE_SHORTCODE_PARAMETER = '@([\w\-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w\-]+)\s*=\s*\'([^\']*)' .
        '\'(?:\s|$)|([\w\-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)@';
    private const REGEX_HAS_IMAGE_EXTENSION = '@\.(?:jpe?g|png|gif|bmp|webp)$@i';

    private ShortcodeStore $shortcodeStore;
    private ExtendedReader $annotationReader;
    private PresetRepository $presetRepository;
    private PresetFinderInterface $presetFinder;
    private ParserInterface $shortcodeParser;
    private ShortcodeRenderer $shortcodeRenderer;
    private TagAwareCacheInterface $cache;

    public function __construct(
        ShortcodeStore $shortcodeStore,
        ExtendedReader $annotationReader,
        PresetRepository $templateRepository,
        PresetFinderInterface $presetFinder,
        ParserInterface $parser,
        ShortcodeRenderer $shortcodeRenderer,
        TagAwareCacheInterface $cache
    ) {
        $this->shortcodeStore = $shortcodeStore;
        $this->annotationReader = $annotationReader;
        $this->presetRepository = $templateRepository;
        $this->presetFinder = $presetFinder;
        $this->shortcodeParser = $parser;
        $this->shortcodeRenderer = $shortcodeRenderer;
        $this->cache = $cache;
    }

    /**
     * @param string $text
     * @return string
     * @throws Exception
     */
    public function applyShortcodes(string $text): string
    {
        $text = $this->insertTextShortcodes($text);

        /** @var ParsedShortcodeInterface[] $parsedShortcodes */
        $parsedShortcodes = $this->cache->get(md5($text), fn () => $this->shortcodeParser->parse($text));

        $renderedText = '';

        foreach ($parsedShortcodes as $parsedShortcode) {
//            $renderedText .= $this->shortcodeRenderer->renderShortcode(
//                $parsedShortcode->getName(),
//                $parsedShortcode->getParameters()
//            );

            $shortcode = $this->shortcodeStore->getShortcode($parsedShortcode->getName());
            $parameters = $parsedShortcode->getParameters();

            if (!$shortcode instanceof TextShortcode && trim((string)$parsedShortcode->getContent())) {
                $parameters['content'] = $this->applyShortcodes((string)$parsedShortcode->getContent());
            }

            $shortcode->setParameters($parameters);
            $renderedText .= $shortcode->render();
        }

        return $renderedText;
    }

    /**
     * @param array $node
     * @return string
     */
    private function renderNode(array $node): string
    {
        if (!empty($node['children'])) {
            $content = '';

            foreach ($node['children'] as $child) {
                $content .= $this->renderNode($child);
            }

            $node['parameters']['content'] = $content;
        }

        /** @var ShortcodeInterface $shortcode */
        $shortcode = $node['shortcode'];
        $shortcode->setParameters($node['parameters']);

        //return $this->shortcodeRenderer->renderShortcode($shortcode, $node['parameters']);
        return $shortcode->render();
    }

    /**
     * @param string $text
     * @param bool $onlyEditables
     * @param bool $storeShortcodeFullString
     * @param bool $isSerialization
     * @return array
     * @throws Exception
     */
    public function buildShortcodeTree(
        string $text,
        bool $onlyEditables = true,
        bool $storeShortcodeFullString = false,
        bool $isSerialization = false
    ): array {
        $text = $this->insertTextShortcodes($text);

        /** @var ParsedShortcodeInterface[] $parsedShortcodes */
        $parsedShortcodes = $this->cache->get(md5($text), fn () => $this->shortcodeParser->parse($text));

        $node = [];
        static $position = 0;

        foreach ($parsedShortcodes as $parsedShortcode) {
            try {
                $shortcode = $this->shortcodeStore->getShortcode($parsedShortcode->getName());
                $shortcodeMetadata = $this->shortcodeStore->getShortcodeMetadata($parsedShortcode->getName());
            } catch (InvalidShortcodeException $e) {
                continue;
            }

            if ($onlyEditables && !$shortcodeMetadata->editable) {
                continue;
            }

            $shortcodeFullString = $parsedShortcode->getText();
            $child = array_merge(
                $isSerialization ? [] : ['shortcode' => $shortcode],
                $this->shortcodeToArray(
                    $parsedShortcode->getName(),
                    $shortcodeFullString,
                    $position++,
                    $isSerialization
                )
            );

            if ($shortcodeMetadata->editable) {
                $child['id'] = Uuid::v4()->toRfc4122();
            }

            if ($shortcodeMetadata->container) {
                $child['children'] = $this->buildShortcodeTree(
                    (string)$parsedShortcode->getContent(),
                    $onlyEditables,
                    $storeShortcodeFullString,
                    $isSerialization
                );
            }

            if ($storeShortcodeFullString) {
                $child['full'] = $parsedShortcode->getText();
            }

            $node[] = $child;
        }

        return $node;
    }

    /**
     * @param string $shortcodeName
     * @param string|null $shortcodeFullString
     * @param int $position
     * @param bool $isSerialization
     * @return array
     * @throws ReflectionException
     */
    public function shortcodeToArray(
        string $shortcodeName,
        string $shortcodeFullString = null,
        int $position = 0,
        bool $isSerialization = false
    ): array {
        $shortcode = $this->shortcodeStore->getShortcode($shortcodeName);
        $shortcodeMetadata = $this->shortcodeStore->getShortcodeMetadata($shortcodeName);
        $shortcodeData = $shortcodeFullString
            ? $this->extractShortcodeData($shortcodeMetadata->name, $shortcodeFullString)
            : null;

        $responsive = $this->getShortcodeResponsiveParameters($shortcode);

        $array = [
            'type' => get_class($shortcode),
            'name' => $shortcodeMetadata->name,
            'parameters' => $shortcode->setParameters(
                is_array($shortcodeData) ? ($shortcodeData[0]['parameters'] ?? []) : [],
                $isSerialization
            )->getParameters($isSerialization),
            'responsive' => $responsive,
            'computed' => [],
            'editable' => $shortcodeMetadata->editable,
            'container' => $shortcodeMetadata->container,
        ];

        if (!$shortcodeMetadata->container) {
            $array['leaf'] = true;
        }

        if ($shortcodeMetadata->editable) {
            $array['id'] = null;
            $array['position'] = $position;
            $array['label'] = $shortcodeMetadata->label;
            $array['siblingsPosition'] = $shortcodeMetadata->siblingsPosition;
            $array['siblingsShortcodes'] = $shortcodeMetadata->siblingsShortcodes;

            $icon = $shortcodeMetadata->icon;
            if (preg_match(self::REGEX_HAS_IMAGE_EXTENSION, $icon)) {
                $array['avatar'] = $icon;
            } else {
                $array['icon'] = $icon;
            }
        }

        return $array;
    }

    private function insertTextShortcodes(string $text): string
    {
        $placeholder = sha1(microtime());

        $parsedShortcodes = $this->shortcodeParser->parse($text);
        foreach ($parsedShortcodes as $parsedShortcode) {
            $text = str_replace($parsedShortcode->getText(), $placeholder, $text);
        }

        $text = '[text]' . str_replace($placeholder, "[/text]{$placeholder}[text]", $text) . '[/text]';
        $text = preg_replace('@\[text]\s*?\[/text]@', '', $text);

        foreach ($parsedShortcodes as $parsedShortcode) {
            $text = preg_replace(
                '/' . preg_quote($placeholder, '/') . '/',
                $parsedShortcode->getText(),
                (string)$text,
                1
            );
        }

        // Remove things such as :
        // <p>[/text]  or  [text]</p>
        $text = preg_replace('@(<\s*[a-zA-Z]+[^>]*>)\s*?\[/text]@simU', '[/text]', (string)$text);
        $text = preg_replace('@\[text]\s*(</\s*[a-zA-Z]+\s*>)@sim', '[text]', (string)$text);

        return (string)$text;
    }

    private function extractShortcodeData(string $shortcodeName, string $text): ?array
    {
        if (
            !preg_match_all(str_replace(
                '%shortcode%',
                $shortcodeName,
                self::REGEX_ISOLATE_FULL_SHORTCODE
            ), $text, $matches, PREG_SET_ORDER)
        ) {
            return null;
        }

        return array_map(
            function ($match) {
                return [
                    'full' => $match[0],
                    'parameters' => array_merge(
                        $this->getShortcodeParameters($match[3]),
                        ['content' => trim($match[5])]
                    )
                ];
            },
            $matches
        );
    }

    private function getShortcodeParameters(string $parameters): array
    {
        if (!preg_match_all(self::REGEX_ISOLATE_SHORTCODE_PARAMETER, $parameters, $matches, PREG_SET_ORDER)) {
            return [];
        }

        return array_reduce(
            $matches,
            static function ($array, $value) {
                $array[$value[1]] = $value[2];
                return $array;
            }
        );
    }

    /**
     * @param string $shortcodeType
     * @param string $text
     * @return array|null
     * @throws Exception
     */
    public function getFirstShortcodeOfType(string $shortcodeType, string $text): ?array
    {
        $shortcodes = $this->buildShortcodeTree($text, false);

        foreach ($shortcodes as $shortcode) {
            if ($shortcode['type'] === $shortcodeType) {
                return $shortcode;
            }
        }

        return null;
    }

    public function getShortcodePresets(string $name): array
    {
        $builtInPresets = $this->presetFinder->findShortcodePresets($this->shortcodeStore->getShortcode($name));
        $presets = array_merge([], ...array_map(
            fn(Preset $preset) => [$preset->getName() => $preset->getContent()],
            $this->presetRepository->findBy(['shortcodeName' => $name])
        ));

        return array_merge($builtInPresets, $presets);
    }

    /**
     * @param ShortcodeInterface $shortcode
     * @return array
     * @throws ReflectionException
     */
    private function getShortcodeResponsiveParameters(ShortcodeInterface $shortcode): array
    {
        $annotations = $this->annotationReader->getAnnotationsOfType($shortcode, Responsive::class);

        return array_keys($annotations);
    }
}
