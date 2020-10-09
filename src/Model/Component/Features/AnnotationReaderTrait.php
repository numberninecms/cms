<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component\Features;

use NumberNine\Annotation\ExtendedReader;

trait AnnotationReaderTrait
{
    protected ExtendedReader $annotationReader;

    final public function setAnnotationReader(ExtendedReader $annotationReader): void
    {
        $this->annotationReader = $annotationReader;
    }
}
