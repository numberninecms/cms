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

namespace NumberNine\Tests\Unit\Content;

use NumberNine\Content\ComponentGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ComponentGeneratorTest extends KernelTestCase
{
    private ComponentGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = static::getContainer()->get(ComponentGenerator::class);
    }

    public function testOutputIsValid(): void
    {
        $result = $this->generator->generate('Dummy');

        static::assertIsArray($result);
        static::assertEquals(['class', 'template'], array_keys($result));
        static::assertEquals($this->getFixtureContent('generated_component_class'), trim($result['class']));
        static::assertEquals($this->getFixtureContent('generated_component_template'), trim($result['template']));
    }

    private function getFixtureContent(string $name): string
    {
        return trim(
            file_get_contents(sprintf('%s/../../Fixtures/Content/ComponentGenerator/%s.txt', __DIR__, $name))
        );
    }
}
