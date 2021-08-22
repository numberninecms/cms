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

namespace NumberNine\Tests\Unit\Theme;

use NumberNine\Entity\Post;
use NumberNine\Entity\Taxonomy;
use NumberNine\Entity\Term;
use NumberNine\Shortcode\TextShortcode;
use NumberNine\Tests\DotEnvAwareWebTestCase;
use NumberNine\Tests\TestServices\SampleShortcode;
use NumberNine\Theme\TemplateResolver;
use Twig\Environment;
use Twig\Error\LoaderError;

final class TemplateResolverTest extends DotEnvAwareWebTestCase
{
    private TemplateResolver $templateResolver;
    private TextShortcode $textShortcode;
    private SampleShortcode $sampleShortcode;
    private Environment $twig;

    public function setUp(): void
    {
        parent::setUp();
        $this->client->request('GET', '/');
        $this->templateResolver = static::getContainer()->get(TemplateResolver::class);
        $this->textShortcode = static::getContainer()->get(TextShortcode::class);
        $this->sampleShortcode = static::getContainer()->get(SampleShortcode::class);
        $this->twig = static::getContainer()->get('twig');
    }

    public function testResolveSinglePostTemplate(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setCustomFields([])
        ;
        $template = $this->templateResolver->resolveSingle($post);

        self::assertEquals('@ChapterOne/post/single.html.twig', $template->getTemplateName());
    }

    public function testResolveSinglePostCustomTemplate(): void
    {
        $post = (new Post())
            ->setCustomType('post')
            ->setCustomFields(['page_template' => 'custom_template.html.twig'])
        ;
        $template = $this->templateResolver->resolveSingle($post, [
            '@NumberNineTests/template_resolver/custom_template.html.twig',
        ]);

        self::assertMatchesRegularExpression(
            '/^custom_template\.html\.twig \(string template [a-f0-9]+\)$/',
            $template->getTemplateName(),
        );
    }

    public function testResolveSinglePageTemplate(): void
    {
        $page = (new Post())
            ->setCustomType('page')
            ->setCustomFields([])
        ;
        $template = $this->templateResolver->resolveSingle($page);

        self::assertEquals('@ChapterOne/page/single.html.twig', $template->getTemplateName());
    }

    public function testResolveSinglePageCustomTemplate(): void
    {
        $page = (new Post())
            ->setCustomType('page')
            ->setCustomFields(['page_template' => 'single.left_sidebar.html.twig'])
        ;
        $template = $this->templateResolver->resolveSingle($page);

        self::assertMatchesRegularExpression(
            '/^single\.left_sidebar\.html\.twig \(string template [a-f0-9]+\)$/',
            $template->getTemplateName(),
        );
    }

    public function testResolveSinglePageCustomExtraTemplate(): void
    {
        $page = (new Post())
            ->setCustomType('page')
            ->setCustomFields(['page_template' => 'custom_template.html.twig'])
        ;
        $template = $this->templateResolver->resolveSingle($page, [
            '@NumberNineTests/template_resolver/custom_template.html.twig',
        ]);

        self::assertMatchesRegularExpression(
            '/^custom_template\.html\.twig \(string template [a-f0-9]+\)$/',
            $template->getTemplateName(),
        );
    }

    public function testResolveSinglePageNonExistentTemplate(): void
    {
        $page = (new Post())
            ->setCustomType('page')
            ->setCustomFields(['page_template' => 'nonexistent_template.html.twig'])
        ;
        $template = $this->templateResolver->resolveSingle($page);

        self::assertEquals('@ChapterOne/page/single.html.twig', $template->getTemplateName());
    }

    public function testResolvePostIndexTemplate(): void
    {
        $template = $this->templateResolver->resolveIndex('post');

        self::assertEquals('@ChapterOne/post/index.html.twig', $template);
    }

    public function testResolvePostIndexCustomTemplate(): void
    {
        $template = $this->templateResolver->resolveIndex('post', [
            '@NumberNineTests/template_resolver/custom_template.html.twig',
        ]);

        self::assertEquals('@NumberNineTests/template_resolver/custom_template.html.twig', $template);
    }

    public function testResolvePageIndexTemplate(): void
    {
        $this->expectException(LoaderError::class);
        $this->templateResolver->resolveIndex('page');
    }

    public function testResolveTermIndexTemplate(): void
    {
        $taxonomy = (new Taxonomy())
            ->setName('test_taxonomy')
            ->setContentTypes(['post'])
        ;

        $term = (new Term())
            ->setTaxonomy($taxonomy)
        ;

        $template = $this->templateResolver->resolveTermIndex($term);

        self::assertEquals('@ChapterOne/post/index.html.twig', $template);
    }

    public function testResolveTermIndexCustomTemplate(): void
    {
        $taxonomy = (new Taxonomy())
            ->setName('test_taxonomy')
            ->setContentTypes(['post'])
        ;

        $term = (new Term())
            ->setTaxonomy($taxonomy)
        ;

        $template = $this->templateResolver->resolveTermIndex($term, [
            '@NumberNineTests/template_resolver/custom_template.html.twig',
        ]);

        self::assertEquals('@NumberNineTests/template_resolver/custom_template.html.twig', $template);
    }

    public function testResolveShortcodeTemplate(): void
    {
        $template = $this->templateResolver->resolveShortcode($this->textShortcode);

        self::assertEquals('@ChapterOneShortcodes/./TextShortcode/template.html.twig', $template);
    }

    public function testResolveShortcodeWithoutThemeTemplate(): void
    {
        $template = $this->templateResolver->resolveShortcode($this->sampleShortcode);

        self::assertStringStartsWith('missing_template (string template', $template);
    }

    public function testResolveShortcodePageBuilderTemplate(): void
    {
        $template = $this->templateResolver->resolveShortcodePageBuilder($this->textShortcode);

        self::assertEquals('@ChapterOneShortcodes/./TextShortcode/template.vue.twig', $template->getTemplateName());
    }

    public function testResolveBaseLayoutTemplate(): void
    {
        $template = $this->templateResolver->resolveBaseLayout();

        self::assertEquals('@ChapterOne/base.html.twig', $template);
    }

    public function testResolvePathTemplate(): void
    {
        $template = $this->templateResolver->resolvePath('base.html.twig');

        self::assertEquals('@ChapterOne/base.html.twig', $template->getTemplateName());
    }
}
