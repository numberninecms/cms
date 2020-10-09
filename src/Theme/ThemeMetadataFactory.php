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
use NumberNine\Annotation\Theme;
use NumberNine\Model\General\AbstractMetadataFactory;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Model\Theme\ThemeDescriptor;
use Symfony\Component\String\Slugger\SluggerInterface;

use function Symfony\Component\String\u;

final class ThemeMetadataFactory extends AbstractMetadataFactory
{
    private SluggerInterface $slugger;

    public function __construct(ExtendedReader $annotationReader, SluggerInterface $slugger)
    {
        parent::__construct($annotationReader);
        $this->slugger = $slugger;
    }

    public function getThemeDescriptor(ThemeInterface $theme): ThemeDescriptor
    {
        /** @var ThemeDescriptor $descriptor */
        $descriptor = parent::getMetadataFor($theme, Theme::class, ThemeDescriptor::class);

        $descriptor->setSlug(u($this->slugger->slug($descriptor->getName()))->lower());

        return $descriptor;
    }
}
