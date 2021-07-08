<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Entity\MediaFile;
use NumberNine\Model\PageBuilder\Control\ImageControl;
use NumberNine\Model\PageBuilder\Control\SliderInputControl;
use NumberNine\Model\PageBuilder\FormControlCriteria;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use NumberNine\Repository\MediaFileRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;
use function NumberNine\Common\Util\ArrayUtil\array_set_if_value_exists;

/**
 * @Shortcode(name="image", label="Image", icon="mdi-image-area")
 */
final class ImageShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    private MediaFileRepository $mediaFileRepository;

    public function __construct(MediaFileRepository $mediaFileRepository)
    {
        $this->mediaFileRepository = $mediaFileRepository;
    }

    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('id', ImageControl::class, [
                'label' => 'Image',
                'fallback_criteria' => (new FormControlCriteria('fromTitle', 'title')),
            ])
            ->add('maxWidth', SliderInputControl::class, [
                'label' => 'Maximum width',
                'max' => 1000,
                'suffix' => 'px',
            ])
            ->add('maxHeight', SliderInputControl::class, [
                'label' => 'Maximum height',
                'max' => 1000,
                'suffix' => 'px',
            ])
            ->add('alt', null, ['label' => 'Alternative text'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'id' => null,
            'fromTitle' => null,
            'maxWidth' => 400,
            'maxHeight' => 200,
            'alt' => null,
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'image' => $this->getImage($parameters),
            'styles' => $this->getStyles($parameters),
            'alt' => $parameters['alt'],
        ];
    }

    private function getImage(array $parameters): ?MediaFile
    {
        if (!$parameters['id'] && !$parameters['fromTitle']) {
            return null;
        }

        if ($parameters['id']) {
            return $this->mediaFileRepository->find($parameters['id']);
        }

        return $this->mediaFileRepository->findOneBy(['title' => $parameters['fromTitle']]);
    }

    private function getStyles(array $parameters): string
    {
        $styles = [];

        array_set_if_value_exists(
            $styles,
            'max-width',
            $parameters['maxWidth'],
            sprintf('%dpx', $parameters['maxWidth'])
        );

        array_set_if_value_exists(
            $styles,
            'max-height',
            $parameters['maxHeight'],
            sprintf('%dpx', $parameters['maxHeight'])
        );

        return array_implode_associative($styles, ';', ':');
    }
}
