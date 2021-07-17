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

namespace NumberNine\Tests\Unit\Form\DataTransformer;

use NumberNine\Form\DataTransformer\SerializerTransformer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SerializerTransformerTest extends KernelTestCase
{
    private SerializerTransformer $serializerTransformer;

    public function setUp(): void
    {
        parent::setUp();
        $this->serializerTransformer = static::getContainer()->get(SerializerTransformer::class);
    }

    public function testTransformWorks(): void
    {
        $transformed = $this->serializerTransformer->transform([
            'id' => 2,
            'name' => 'Sample name',
            'children' => [],
        ]);

        $expected = '{"id":2,"name":"Sample name","children":[]}';

        self::assertEquals($expected, $transformed);
    }

    public function testReverseTransformWorks(): void
    {
        $transformed = $this->serializerTransformer->reverseTransform('{"id":2,"name":"Sample name","children":[]}');

        $expected = [
            'id' => 2,
            'name' => 'Sample name',
            'children' => [],
        ];

        self::assertEquals($expected, $transformed);
    }
}
