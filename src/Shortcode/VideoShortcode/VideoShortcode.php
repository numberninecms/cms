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
use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;
use Symfony\Component\Mime\MimeTypes;

/**
 * @Shortcode(name="video", label="Video", editable=true, icon="movie")
 */
final class VideoShortcode extends AbstractShortcode
{
    /**
     * @Control\TextBox(label="Url")
     */
    public string $src = '';

    /**
     * @Control\TextBox(label="Width")
     */
    public string $width = '100%';

    /**
     * @Control\TextBox(label="Height")
     */
    public string $height = '';

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        $mimeTypes = new MimeTypes();
        return $this->src && file_exists($this->src) ? (string)$mimeTypes->guessMimeType($this->src) : '';
    }
}
