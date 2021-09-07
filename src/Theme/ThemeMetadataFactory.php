<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Theme;

use NumberNine\Annotation\ExtendedReader;
use NumberNine\Attribute\Theme;
use NumberNine\Model\General\AbstractMetadataFactory;
use NumberNine\Model\Theme\ThemeDescriptor;
use NumberNine\Model\Theme\ThemeInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\String\u;

final class ThemeMetadataFactory extends AbstractMetadataFactory
{
    public function __construct(ExtendedReader $annotationReader, private SluggerInterface $slugger)
    {
        parent::__construct($annotationReader);
    }

    public function getThemeDescriptor(ThemeInterface $theme): ThemeDescriptor
    {
        /** @var ThemeDescriptor $descriptor */
        $descriptor = $this->getMetadataFor($theme, Theme::class, ThemeDescriptor::class);

        $descriptor->setSlug(u($this->slugger->slug($descriptor->getName()))->lower());

        return $descriptor;
    }
}
