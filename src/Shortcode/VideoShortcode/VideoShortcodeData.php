<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\VideoShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class VideoShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="Url")
     */
    protected string $src = '';

    /**
     * @Control\TextBox(label="Width")
     */
    protected string $width = '100%';

    /**
     * @Control\TextBox(label="Height")
     */
    protected string $height = '';

    /**
     * @return string
     */
    protected function getMimeType(): string
    {
        $mimeTypes = new MimeTypes();
        return $this->src && file_exists($this->src) ? (string)$mimeTypes->guessMimeType($this->src) : '';
    }

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'src' => '',
            'width' => '100%',
            'height' => '',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'src' => $this->src,
            'width' => $this->width,
            'height' => $this->height,
            'mimeType' => $this->getMimeType(),
        ];
    }
}
