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

namespace NumberNine\Tests\Unit\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use NumberNine\Annotation\ExtendedAnnotationReader;
use NumberNine\Attribute\Theme;
use NumberNine\Exception\AnnotationOrAttributeMissingException;
use NumberNine\Tests\Dummy\Annotation\SampleAnnotation;
use NumberNine\Tests\Dummy\Annotation\UnusedAnnotation;
use NumberNine\Tests\Dummy\Attribute\AnotherSampleAttribute;
use NumberNine\Tests\Dummy\Attribute\SampleAttribute;
use NumberNine\Tests\Dummy\Model\SampleAnnotatedClass;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \NumberNine\Annotation\ExtendedAnnotationReader
 *
 * @internal
 */
final class ExtendedAnnotationReaderTest extends TestCase
{
    private ExtendedAnnotationReader $reader;
    private SampleAnnotation $classAnnotation;
    private SampleAnnotation $propertyAnnotation;
    private SampleAnnotation $methodAnnotation;
    private SampleAttribute $classAttribute;
    private SampleAttribute $propertyAttribute;
    private SampleAttribute $methodAttribute;
    private AnotherSampleAttribute $anotherClassAttribute;
    private AnotherSampleAttribute $anotherPropertyAttribute;

    protected function setUp(): void
    {
        parent::setUp();
        $annotationReader = new AnnotationReader();
        $this->reader = new ExtendedAnnotationReader($annotationReader);

        $this->classAnnotation = new SampleAnnotation();
        $this->classAnnotation->name = 'Sample class annotation';
        $this->classAnnotation->value = 2.6;

        $this->propertyAnnotation = new SampleAnnotation();
        $this->propertyAnnotation->name = 'Sample property annotation';
        $this->propertyAnnotation->value = 6.9;

        $this->methodAnnotation = new SampleAnnotation();
        $this->methodAnnotation->name = 'Sample method annotation';
        $this->methodAnnotation->value = 15.3;

        $this->classAttribute = new SampleAttribute('Sample class attribute', 4.2);
        $this->propertyAttribute = new SampleAttribute('Sample property attribute', 13.0);
        $this->methodAttribute = new SampleAttribute('Sample method attribute', 7.6);

        $this->anotherClassAttribute = new AnotherSampleAttribute('Class', false);
        $this->anotherPropertyAttribute = new AnotherSampleAttribute('Property', true);
    }

    /**
     * @covers ::getAllAnnotationsAndAttributes
     */
    public function testGetAllAnnotations(): void
    {
        static::assertSame(
            [
                SampleAnnotatedClass::class => [$this->classAnnotation, $this->classAttribute],
                'sampleProperty' => [$this->propertyAnnotation, $this->propertyAttribute],
                'sampleMethod' => [$this->methodAnnotation, $this->methodAttribute],
            ],
            $this->reader->getAllAnnotationsAndAttributes(SampleAnnotatedClass::class),
        );
    }

    /**
     * @covers ::getAnnotationsOrAttributesOfType
     */
    public function testGetAnnotationsOrAttributesOfType(): void
    {
        static::assertSame(
            [
                SampleAnnotatedClass::class => $this->classAnnotation,
                'sampleProperty' => $this->propertyAnnotation,
                'sampleMethod' => $this->methodAnnotation,
            ],
            $this->reader->getAnnotationsOrAttributesOfType(SampleAnnotatedClass::class, SampleAnnotation::class)
        );

        static::assertSame(
            [
                SampleAnnotatedClass::class => $this->anotherClassAttribute,
                'sampleProperty' => $this->anotherPropertyAttribute,
            ],
            $this->reader->getAnnotationsOrAttributesOfType(SampleAnnotatedClass::class, AnotherSampleAttribute::class)
        );
    }

    /**
     * @covers ::getFirstAnnotationOrAttributeOfType
     */
    public function testGetFirstAnnotationOrAttributeOfType(): void
    {
        static::assertSame(
            $this->classAnnotation,
            $this->reader->getFirstAnnotationOrAttributeOfType(SampleAnnotatedClass::class, SampleAnnotation::class)
        );

        static::assertSame(
            $this->anotherClassAttribute,
            $this->reader->getFirstAnnotationOrAttributeOfType(
                SampleAnnotatedClass::class,
                AnotherSampleAttribute::class,
            ),
        );
    }

    /**
     * @covers ::getFirstAnnotationOrAttributeOfType
     */
    public function testGetFirstAnnotationOfTypeThrowException(): void
    {
        $this->expectException(AnnotationOrAttributeMissingException::class);
        static::assertSame(
            $this->classAnnotation,
            $this->reader->getFirstAnnotationOrAttributeOfType(
                SampleAnnotatedClass::class,
                UnusedAnnotation::class,
                true,
            )
        );
    }

    /**
     * @covers ::getFirstAnnotationOrAttributeOfType
     */
    public function testGetFirstAttributeOfTypeThrowException(): void
    {
        $this->expectException(AnnotationOrAttributeMissingException::class);
        static::assertSame(
            $this->classAnnotation,
            $this->reader->getFirstAnnotationOrAttributeOfType(SampleAnnotatedClass::class, Theme::class, true,)
        );
    }

    /**
     * @covers ::getValueOfFirstAnnotationOrAttributeOfType
     */
    public function testGetValueOfFirstAnnotationOrAttributeOfType(): void
    {
        static::assertSame(
            'Sample class annotation',
            $this->reader->getValueOfFirstAnnotationOrAttributeOfType(
                SampleAnnotatedClass::class,
                SampleAnnotation::class,
                null,
                'name',
            )
        );

        static::assertFalse(
            $this->reader->getValueOfFirstAnnotationOrAttributeOfType(
                SampleAnnotatedClass::class,
                AnotherSampleAttribute::class,
                null,
                'nullable',
            ),
        );
    }
}
