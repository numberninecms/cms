<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Tests\Dummy\Model;

use NumberNine\Tests\Dummy\Annotation\SampleAnnotation;
use NumberNine\Tests\Dummy\Annotation\AnotherSampleAnnotation;
use NumberNine\Tests\Dummy\Attribute\AnotherSampleAttribute;
use NumberNine\Tests\Dummy\Attribute\SampleAttribute;

/**
 * @SampleAnnotation(name="Sample class annotation", value=2.6)
 * @AnotherSampleAnnotation(category="Class", nullable=false)
 */
#[SampleAttribute(name: 'Sample class attribute', value: 4.2)]
#[AnotherSampleAttribute(category: 'Class', nullable: false)]
final class SampleAnnotatedClass
{
    /**
     * @SampleAnnotation(name="Sample property annotation", value=6.9)
     * @AnotherSampleAnnotation(category="Property", nullable=true)
     */
    #[SampleAttribute(name: 'Sample property attribute', value: 13.0)]
    #[AnotherSampleAttribute(category: 'Property', nullable: true)]
    private string $sampleProperty;

    /**
     * @SampleAnnotation(name="Sample method annotation", value=15.3)
     */
    #[SampleAttribute(name: 'Sample method attribute', value: 7.6)]
    public function sampleMethod(): void {
    }
}
