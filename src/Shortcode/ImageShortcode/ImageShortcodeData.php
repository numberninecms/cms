<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\ImageShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Content\ShortcodeData;
use NumberNine\Entity\MediaFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Common\Util\ArrayUtil\array_set_if_value_exists;

final class ImageShortcodeData extends ShortcodeData
{
    /**
     * @Control\Image(label="Image")
     */
    protected ?int $id;

    protected ?string $fromTitle;

    /**
     * @Exclude("serialization")
     */
    protected ?MediaFile $image;

    /**
     * @Control\SliderInput(label="Maximum width", max=1000, suffix="px")
     */
    protected int $maxWidth;

    /**
     * @Control\SliderInput(label="Maximum height", max=1000.0, suffix="px")
     */
    protected int $maxHeight;

    /**
     * @Control\TextBox(label="Alternative text")
     */
    protected ?string $alt;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'id' => null,
            'fromTitle' => null,
            'maxWidth' => 400,
            'maxHeight' => 200,
            'alt' => null,
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'image' => $this->image,
            'styles' => $this->getStyles(),
            'alt' => $this->alt,
        ];
    }

    /**
     * @Exclude("serialization")
     */
    protected function getStyles(): string
    {
        $styles = [];

        array_set_if_value_exists($styles, 'max-width', $this->maxWidth, sprintf('%dpx', $this->maxWidth));
        array_set_if_value_exists($styles, 'max-height', $this->maxHeight, sprintf('%dpx', $this->maxHeight));

        return array_implode_associative($styles, ';', ':');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromTitle(): ?string
    {
        return $this->fromTitle;
    }

    public function setImage(?MediaFile $image): void
    {
        $this->image = $image;
    }
}
