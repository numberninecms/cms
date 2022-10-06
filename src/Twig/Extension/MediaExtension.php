<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MediaExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('N9_image', [MediaRuntime::class, 'getImage'], ['is_safe' => ['html']]),
            new TwigFunction('N9_image_url', [MediaRuntime::class, 'getImageUrl']),
            new TwigFunction('N9_supports_featured_image', [MediaRuntime::class, 'supportsFeaturedImage']),
            new TwigFunction('N9_featured_image', [MediaRuntime::class, 'getFeaturedImage'], ['is_safe' => ['html']]),
            new TwigFunction('N9_max_upload_size', [MediaRuntime::class, 'getMaxUploadSize']),
        ];
    }
}
