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
use NumberNine\Attribute\Form\Responsive;
use NumberNine\Entity\Preset;
use NumberNine\Exception\InvalidShortcodeException;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Repository\PresetRepository;
use NumberNine\Theme\PresetFinderInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Uid\Uuid;
use Thunder\Shortcode\Parser\ParserInterface;
use Thunder\Shortcode\Shortcode\ParsedShortcodeInterface;

final class ShortcodeProcessor
{
    private const REGEX_HAS_IMAGE_EXTENSION = '@\.(?:jpe?g|png|gif|bmp|webp)$@i';

    public function __construct(private ShortcodeStore $shortcodeStore, private ExtendedReader $annotationReader, private PresetRepository $presetRepository, private PresetFinderInterface $presetFinder, private ParserInterface $shortcodeParser, private TagAwareCacheInterface $cache)
    {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function buildShortcodeTree(
        string $text,
        bool $onlyEditables = true,
        bool $storeShortcodeFullString = false,
        bool $isSerialization = false
    ): array {
        $text = $this->insertTextShortcodes($text);

        /** @var ParsedShortcodeInterface[] $parsedShortcodes */
        $parsedShortcodes = $this->cache->get(md5($text), fn (): array => $this->shortcodeParser->parse($text));

        $node = [];
        static $position = 0;

        foreach ($parsedShortcodes as $parsedShortcode) {
            try {
                $shortcode = $this->shortcodeStore->getShortcode($parsedShortcode->getName());
                $shortcodeMetadata = $this->shortcodeStore->getShortcodeMetadata($parsedShortcode->getName());
            } catch (InvalidShortcodeException) {
                continue;
            }

            $editable = is_subclass_of($shortcode, EditableShortcodeInterface::class);

            if ($onlyEditables && !$editable) {
                continue;
            }

            $parameters = $parsedShortcode->getParameters();

            if ($parsedShortcode->getContent()) {
                $parameters['content'] = $parsedShortcode->getContent();
            }

            $child = array_merge(
                $isSerialization ? [] : ['shortcode' => $shortcode],
                $this->shortcodeToArray(
                    $parsedShortcode->getName(),
                    $parameters,
                    $position++
                )
            );

            if ($editable) {
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

    public function shortcodeToArray(string $shortcodeName, array $parameters = [], int $position = 0): array
    {
        $shortcode = $this->shortcodeStore->getShortcode($shortcodeName);
        $shortcodeMetadata = $this->shortcodeStore->getShortcodeMetadata($shortcodeName);
        $responsive = $this->getShortcodeResponsiveParameters($shortcode);
        $editable = is_subclass_of($shortcode, EditableShortcodeInterface::class);

        $resolver = new OptionsResolver();
        $resolver->setDefined(array_keys($parameters));
        $shortcode->configureParameters($resolver);
        $parameters = $resolver->resolve($parameters);

        $array = [
            'type' => $shortcode::class,
            'name' => $shortcodeMetadata->name,
            'parameters' => $parameters,
            'responsive' => $responsive,
            'computed' => [],
            'editable' => $editable,
            'container' => $shortcodeMetadata->container,
        ];

        if (!$shortcodeMetadata->container) {
            $array['leaf'] = true;
        }

        if ($editable) {
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

    public function insertTextShortcodes(string $text): string
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

    /**
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

    private function getShortcodeResponsiveParameters(ShortcodeInterface $shortcode): array
    {
        $annotations = $this->annotationReader->getAnnotationsOrAttributesOfType($shortcode, Responsive::class);

        return array_keys($annotations);
    }
}
