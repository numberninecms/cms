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

use NumberNine\Annotation\Shortcode\Exclude;
use NumberNine\Annotation\Shortcode\ExclusionPolicy;
use NumberNine\Annotation\Shortcode\Expose;
use NumberNine\Model\Component\RenderableInterface;
use NumberNine\Model\Inspector\InspectedRenderable;
use NumberNine\Annotation\ExtendedReader;

final class RenderableInspector implements RenderableInspectorInterface
{
    private ExtendedReader $annotationReader;

    public function __construct(ExtendedReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param RenderableInterface $renderable
     * @param bool $isSerialization
     * @return InspectedRenderable
     */
    public function inspect(RenderableInterface $renderable, bool $isSerialization = false): InspectedRenderable
    {
        $annotations = $this->annotationReader->getAllAnnotations($renderable);
        $exclusionPolicy = $this->annotationReader->getValueOfFirstAnnotationOfType($annotations, ExclusionPolicy::class, 'none');

        $excludes = $exclusionPolicy === ExclusionPolicy::NONE ? $this->annotationReader->getAnnotationsOfType($annotations, Exclude::class) : [];
        $exposes = $exclusionPolicy === ExclusionPolicy::ALL ? $this->annotationReader->getAnnotationsOfType($annotations, Expose::class) : [];

        return new InspectedRenderable($renderable, $exclusionPolicy, $excludes, $exposes, $isSerialization);
    }
}
