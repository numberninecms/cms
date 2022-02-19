<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Tests\Unit\Content;

use NumberNine\Content\ShortcodeRenderer;
use NumberNine\Shortcode\TextShortcode;
use NumberNine\Tests\Dummy\Shortcode\SampleShortcode;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ShortcodeRendererTest extends WebTestCase
{
    private KernelBrowser $client;
    private ShortcodeRenderer $shortcodeRenderer;
    private TextShortcode $textShortcode;
    private SampleShortcode $sampleShortcode;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/');
        $this->shortcodeRenderer = static::getContainer()->get(ShortcodeRenderer::class);
        $this->textShortcode = static::getContainer()->get(TextShortcode::class);
        $this->sampleShortcode = static::getContainer()->get(SampleShortcode::class);
    }

    public function testRenderPageBuilderTemplateWhenExists(): void
    {
        $output = $this->shortcodeRenderer->renderPageBuilderTemplate($this->textShortcode);

        static::assertSame('<div v-html="parameters.content"></div>', $output);
    }
}
