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

use NumberNine\Content\ShortcodeGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ShortcodeGeneratorTest extends KernelTestCase
{
    private ShortcodeGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = static::getContainer()->get(ShortcodeGenerator::class);
    }

    public function testOutputIsValid(): void
    {
        $result = $this->generator->generate('TurtleShortcode', [
            'name' => 'turtle',
            'label' => 'Turtle',
            'container' => false,
            'editable' => true,
            'icon' => 'mdi-tortoise',
        ]);

        static::assertIsArray($result);
        static::assertEquals(['class', 'template_html', 'template_vue'], array_keys($result));
        static::assertEquals($this->getFixtureContent('generated_shortcode_class'), trim($result['class']));
        static::assertEquals(
            $this->getFixtureContent('generated_shortcode_template_html'),
            trim($result['template_html'])
        );
        static::assertEquals(
            $this->getFixtureContent('generated_shortcode_template_vue'),
            trim($result['template_vue'])
        );
    }

    public function testNonEditableShortcodeDoesNotImplementInterface(): void
    {
        $result = $this->generator->generate('TurtleShortcode', [
            'name' => 'turtle',
            'label' => 'Turtle',
            'container' => false,
            'editable' => false,
            'icon' => 'mdi-tortoise',
        ]);

        static::assertStringNotContainsString('EditableShortcodeInterface', $result['class']);
    }

    public function testContainerShortcodeHasAttributeParameter(): void
    {
        $result = $this->generator->generate('TurtleShortcode', [
            'name' => 'turtle',
            'label' => 'Turtle',
            'container' => true,
        ]);

        static::assertStringContainsString(
            "#[Shortcode(name: 'turtle', label: 'Turtle', container: true)]",
            $result['class'],
        );
    }

    public function testIconIsIgnoredForNonEditableShortcode(): void
    {
        $result = $this->generator->generate('TurtleShortcode', [
            'name' => 'turtle',
            'label' => 'Turtle',
            'icon' => 'mdi-tortoise',
        ]);

        static::assertStringContainsString("#[Shortcode(name: 'turtle', label: 'Turtle')]", $result['class']);
    }

    public function testIconIsPresentForEditableShortcode(): void
    {
        $result = $this->generator->generate('TurtleShortcode', [
            'name' => 'turtle',
            'label' => 'Turtle',
            'editable' => true,
            'icon' => 'mdi-tortoise',
        ]);

        static::assertStringContainsString(
            "#[Shortcode(name: 'turtle', label: 'Turtle', icon: 'mdi-tortoise')]",
            $result['class'],
        );
    }

    private function getFixtureContent(string $name): string
    {
        return trim(
            file_get_contents(sprintf('%s/../../Fixtures/Content/ShortcodeGenerator/%s.txt', __DIR__, $name))
        );
    }
}
