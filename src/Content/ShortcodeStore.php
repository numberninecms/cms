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

use NumberNine\Annotation\Shortcode;
use NumberNine\Exception\InvalidShortcodeException;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Annotation\ExtendedReader;
use ReflectionException;

final class ShortcodeStore
{
    private ExtendedReader $annotationReader;

    /** @var ShortcodeInterface[] */
    private array $shortcodes = [];

    /** @var Shortcode[] */
    private array $shortcodesMetadata = [];

    public function __construct(ExtendedReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @return ShortcodeInterface[]
     */
    public function getShortcodes(): array
    {
        return $this->shortcodes;
    }

    /**
     * @return Shortcode[]
     */
    public function getShortcodesMetadata(): array
    {
        return $this->shortcodesMetadata;
    }

    public function getShortcodeMetadata(string $shortcodeName): Shortcode
    {
        if (!$this->hasShortcodeMetadata($shortcodeName)) {
            throw new InvalidShortcodeException($shortcodeName);
        }

        return $this->shortcodesMetadata[$shortcodeName];
    }

    public function getShortcode(string $shortcodeName): ShortcodeInterface
    {
        if (!$this->hasShortcode($shortcodeName)) {
            throw new InvalidShortcodeException($shortcodeName);
        }

        return $this->shortcodes[$shortcodeName];
    }

    /**
     * @param ShortcodeInterface $shortcode
     * @throws ReflectionException
     */
    public function addShortcode(ShortcodeInterface $shortcode): void
    {
        $metadata = $this->annotationReader->getFirstAnnotationOfType($shortcode, Shortcode::class, true);

        $this->shortcodes[$metadata->name] = $shortcode;
        $this->shortcodesMetadata[$metadata->name] = $metadata;
    }

    public function hasShortcode(string $shortcodeName): bool
    {
        return array_key_exists($shortcodeName, $this->shortcodes);
    }

    public function hasShortcodeMetadata(string $shortcodeName): bool
    {
        return array_key_exists($shortcodeName, $this->shortcodesMetadata);
    }
}
