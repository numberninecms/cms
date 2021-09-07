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

use NumberNine\Attribute\Shortcode;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[Shortcode(name: 'video', label: 'Video', icon: 'mdi-video')]
final class VideoShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('src', null, ['label' => 'Url'])
            ->add('width')
            ->add('height')
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'src' => '',
            'width' => '100%',
            'height' => '',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'src' => $parameters['src'],
            'width' => $parameters['width'],
            'height' => $parameters['height'],
            'mimeType' => $this->getMimeType($parameters),
        ];
    }

    private function getMimeType(array $parameters): string
    {
        $mimeTypes = new MimeTypes();

        return $parameters['src'] && file_exists($parameters['src'])
            ? (string) $mimeTypes->guessMimeType($parameters['src'])
            : '';
    }
}
