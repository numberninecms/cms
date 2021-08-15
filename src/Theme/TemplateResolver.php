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

use InvalidArgumentException;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\Term;
use NumberNine\Exception\ContentTypeNotFoundException;
use NumberNine\Model\Component\ComponentInterface;
use NumberNine\Model\Content\ContentType;
use NumberNine\Model\Shortcode\ShortcodeInterface;
use NumberNine\Content\ContentService;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;
use Twig\TemplateWrapper;

use function Symfony\Component\String\u;

final class TemplateResolver implements TemplateResolverInterface
{
    private Environment $twig;
    private ThemeStore $themeStore;
    private ContentService $contentService;
    private TagAwareCacheInterface $cache;
    private SluggerInterface $slugger;
    private array $bundles;
    private string $templatePath;

    public function __construct(
        Environment $twig,
        ThemeStore $themeStore,
        ContentService $contentService,
        TagAwareCacheInterface $cache,
        SluggerInterface $slugger,
        array $bundles,
        string $templatePath
    ) {
        $this->twig = $twig;
        $this->themeStore = $themeStore;
        $this->contentService = $contentService;
        $this->cache = $cache;
        $this->slugger = $slugger;
        $this->bundles = $bundles;
        $this->templatePath = $templatePath;
    }

    public function resolveSingle(ContentEntity $entity, array $extraTemplates = []): TemplateWrapper
    {
        return $this->cache->get(
            sprintf('single_%s_id_%d', $entity->getCustomType(), $entity->getId()),
            function (ItemInterface $item) use ($entity, $extraTemplates) {
                $item->tag(sprintf('content_entity_%d', $entity->getId()));

                $themeName = $this->themeStore->getCurrentThemeName();

                $templates = array_merge(
                    $extraTemplates,
                    [
                        sprintf('theme/%s/single_%s.html.twig', $entity->getCustomType(), $entity->getSlug()),
                        sprintf('@%s/%s/single_%s.html.twig', $themeName, $entity->getCustomType(), $entity->getSlug()),
                        sprintf('theme/%s/single_%d.html.twig', $entity->getCustomType(), $entity->getId()),
                        sprintf('@%s/%s/single_%d.html.twig', $themeName, $entity->getCustomType(), $entity->getId()),
                        sprintf('theme/%s/single.html.twig', $entity->getCustomType()),
                        sprintf('@%s/%s/single.html.twig', $themeName, $entity->getCustomType()),
                        sprintf('theme/content/single.html.twig'),
                        sprintf('@%s/content/single.html.twig', $themeName),
                    ]
                );

                if ($pageTemplate = $entity->getCustomField('page_template')) {
                    $candidates = array_merge(
                        $this->getContentEntitySingleTemplateCandidates(
                            $this->contentService->getContentType((string)$entity->getCustomType())
                        ),
                        $this->getContentEntityIndexTemplateCandidates(),
                    );

                    $filename = \array_key_exists($pageTemplate, $candidates) ? $pageTemplate : false;

                    if (\is_string($filename) && $filename) {
                        if (preg_match('/^index\./', basename($filename))) {
                            array_unshift(
                                $templates,
                                sprintf('theme/%s', $filename),
                                sprintf('@%s/%s', $themeName, $filename),
                            );
                        } else {
                            array_unshift(
                                $templates,
                                sprintf('theme/%s/%s', $entity->getCustomType(), $filename),
                                sprintf('@%s/%s/%s', $themeName, $entity->getCustomType(), $filename),
                                sprintf('theme/content/%s', $filename),
                            );
                        }
                    }
                }

                try {
                    $template = $this->twig->resolveTemplate($templates);

                    if ($this->hasFrontMatterBlock($template->getSourceContext()->getCode())) {
                        $template = $this->createTwigTemplateFromFrontMatterAnnotatedFile(
                            $template->getSourceContext()->getPath()
                        );
                    }
                } catch (SyntaxError $e) {
                    if (($context = $e->getSourceContext()) && $this->hasFrontMatterBlock($context->getCode())) {
                        $template = $this->createTwigTemplateFromFrontMatterAnnotatedFile($context->getPath());
                    } else {
                        throw $e;
                    }
                }

                return $template;
            }
        );
    }

    /**
     * @param string $customType
     * @param array $extraTemplates
     * @return string
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function resolveIndex(string $customType, array $extraTemplates = []): string
    {
        $themeName = $this->themeStore->getCurrentThemeName();

        $templates = array_merge(
            $extraTemplates,
            [
                sprintf('theme/%s/index.html.twig', $customType),
                sprintf('@%s/%s/index.html.twig', $themeName, $customType),
                sprintf('theme/content/index.html.twig'),
                sprintf('@%s/content/index.html.twig', $themeName),
            ]
        );

        return $this->twig->resolveTemplate($templates)->getTemplateName();
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function resolveTermIndex(Term $term, array $extraTemplates = []): string
    {
        $themeName = $this->themeStore->getCurrentThemeName();
        $taxonomy = $term->getTaxonomy();

        if (!$taxonomy) {
            throw new RuntimeException(sprintf('Term "%s" doesn\'t have a taxonomy.', $term->getName()));
        }

        $contentTypes = $taxonomy->getContentTypes() ?? [];
        $templates = [];

        foreach ($contentTypes as $contentType) {
            $templates[] = [
                sprintf('theme/%s/index_%s_%s.html.twig', $contentType, $taxonomy->getName(), $term->getSlug()),
                sprintf(
                    '@%s/%s/index_%s_%s.html.twig',
                    $themeName,
                    $contentType,
                    $taxonomy->getName(),
                    $term->getSlug()
                ),
                sprintf('theme/%s/index_%s_%d.html.twig', $contentType, $taxonomy->getName(), $term->getId()),
                sprintf('@%s/%s/index_%s_%d.html.twig', $themeName, $contentType, $taxonomy->getName(), $term->getId()),
                sprintf('theme/%s/index_%s.html.twig', $contentType, $taxonomy->getName()),
                sprintf('@%s/%s/index_%s.html.twig', $themeName, $contentType, $taxonomy->getName()),
                sprintf('theme/%s/index.html.twig', $contentType),
                sprintf('@%s/%s/index.html.twig', $themeName, $contentType),
                sprintf('theme/content/index.html.twig'),
                sprintf('@%s/content/index.html.twig', $themeName),
            ];
        }

        $templates = array_unique(array_merge($extraTemplates, ...$templates));

        return $this->twig->resolveTemplate($templates)->getTemplateName();
    }

    public function resolveComponent(ComponentInterface $component): string
    {
        return $this->cache->get($this->slugger->slug(\get_class($component)), function () use ($component) {
            $templates = $this->getComponentTemplatesCandidates($component);
            return $this->twig->resolveTemplate($templates)->getTemplateName();
        });
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function resolveShortcode(ShortcodeInterface $shortcode): string
    {
        return $this->resolveShortcodeTemplate($shortcode, 'html')->getTemplateName();
    }

    /**
     * @param ShortcodeInterface $shortcode
     * @return TemplateWrapper
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function resolveShortcodePageBuilder(ShortcodeInterface $shortcode): TemplateWrapper
    {
        return $this->resolveShortcodeTemplate($shortcode, 'vue');
    }

    /**
     * @param ShortcodeInterface $shortcode
     * @param string $type
     * @return TemplateWrapper
     * @throws LoaderError
     * @throws SyntaxError
     */
    private function resolveShortcodeTemplate(ShortcodeInterface $shortcode, string $type): TemplateWrapper
    {
        $templates = $this->getShortcodeTemplatesCandidates($shortcode, $type);
        return $this->twig->resolveTemplate($templates);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function resolveBaseLayout(): string
    {
        return $this->resolvePath('base.html.twig')->getTemplateName();
    }

    /**
     * @param string $path
     * @return TemplateWrapper
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function resolvePath(string $path): TemplateWrapper
    {
        $themeName = $this->themeStore->getCurrentThemeName();

        $templates = [
            sprintf('theme/%s', $path),
            sprintf('@%s/%s', $themeName, $path),
            sprintf('@NumberNine/%s', $path),
        ];

        return $this->twig->resolveTemplate($templates);
    }

    public function getShortcodeTemplatesCandidates(ShortcodeInterface $shortcode, string $type): array
    {
        if (!\in_array($type, ['html', 'vue'])) {
            throw new InvalidArgumentException("Type must be 'html' or 'vue'.");
        }

        $themeName = $this->themeStore->getCurrentThemeName();
        $shortcodeReflection = new ReflectionClass($shortcode);

        $subNamespace = sprintf(
            '%s/%s',
            \dirname(
                str_replace(
                    '\\',
                    '/',
                    substr(
                        $shortcodeReflection->getName(),
                        strpos($shortcodeReflection->getName(), '\\Shortcode\\') + 11
                    )
                )
            ),
            basename($shortcodeReflection->getShortName())
        );

        $templates = [
            sprintf('@AppShortcodes/%s/template.%s.twig', $subNamespace, $type),
            sprintf('@%sShortcodes/%s/template.%s.twig', $themeName, $subNamespace, $type),
        ];

        foreach ($this->bundles as $bundle => $fqcn) {
            $templates[] = sprintf(
                '@%sShortcodes/%s/template.%s.twig',
                str_replace('Bundle', '', $bundle),
                $subNamespace,
                $type
            );
        }

        $templates[] = sprintf('@NumberNineShortcodes/%s/template.%s.twig', $subNamespace, $type);

        return $templates;
    }

    public function getComponentTemplatesCandidates(ComponentInterface $component): array
    {
        $themeName = $this->themeStore->getCurrentThemeName();
        $reflectionClass = new ReflectionClass($component);

        $subNamespace = \dirname(
            str_replace(
                '\\',
                '/',
                substr($reflectionClass->getName(), strpos($reflectionClass->getName(), '\\Component\\') + 11)
            )
        );

        $templates = [
            sprintf('@AppComponents/%s/template.html.twig', $subNamespace),
            sprintf('@%sComponents/%s/template.html.twig', $themeName, $subNamespace),
        ];

        foreach ($this->bundles as $bundle => $fqcn) {
            $templates[] = sprintf(
                '@%sComponents/%s/template.html.twig',
                str_replace('Bundle', '', $bundle),
                $subNamespace
            );
        }

        return $templates;
    }

    public function getContentEntitySingleTemplateCandidates(ContentType $type): array
    {
        $directories = [
            sprintf('%s/theme/%s/', $this->templatePath, $type->getName()),
            sprintf('%s%s/', $this->themeStore->getCurrentTheme()->getTemplatePath(), $type->getName()),
        ];

        $templateFiles = [];
        $templatesNames = [];

        foreach ($directories as $directory) {
            if (!file_exists($directory) || !is_dir($directory)) {
                continue;
            }

            $finder = new Finder();
            $finder->in($directory)->files()->name('single.*.twig');
            $templateFiles[] = array_keys(iterator_to_array($finder, true));
        }

        $templateFiles = array_unique(array_merge([], ...$templateFiles));

        foreach ($templateFiles as $templateFile) {
            $content = file_get_contents($templateFile);
            $templateInfo = [];

            if ($this->hasFrontMatterBlock((string)$content, $matches)) {
                $templateInfo = Yaml::parse($matches[1]);
            }

            $basename = basename($templateFile);

            if (!empty($templateInfo['template']) && !\array_key_exists($basename, $templatesNames)) {
                $templatesNames[$basename] = $templateInfo['template'];
            }
        }

        return $templatesNames;
    }

    public function getContentEntityIndexTemplateCandidates(): array
    {
        $directoriesForIndexPages = [
            sprintf('%s/theme/', $this->templatePath),
            sprintf('%s/', $this->themeStore->getCurrentTheme()->getTemplatePath()),
        ];

        $templateFiles = [];
        $templatesNames = [];

        foreach ($directoriesForIndexPages as $directory) {
            if (!file_exists($directory) || !is_dir($directory)) {
                continue;
            }

            $finder = new Finder();
            $finder->in($directory)->files()->name('index.*.twig');
            $templateFiles[] = array_keys(iterator_to_array($finder, true));
        }

        $templateFiles = array_unique(array_merge([], ...$templateFiles));

        foreach ($templateFiles as $templateFile) {
            if (!preg_match('@/([\w_-]+)/(index\.[\w_-]+\.twig$)@', $templateFile, $matches)) {
                continue;
            }

            try {
                $contentType = $this->contentService->getContentType($matches[1]);

                $templatesNames[trim($matches[0], '/')] = sprintf(
                    '%s index page',
                    u($contentType->getLabels()->getPluralName())->title(),
                );
            } catch (ContentTypeNotFoundException $e) {
            }
        }

        return $templatesNames;
    }


    private function hasFrontMatterBlock(string $template, array &$matches = null): bool
    {
        return preg_match('@^---[\r\n]+(.*)\s---[\r\n]+@simU', $template, $matches) > 0;
    }

    private function createTwigTemplateFromFrontMatterAnnotatedFile(string $filename): TemplateWrapper
    {
        return $this->twig->createTemplate(
            (string)preg_replace('@^(---[\r\n]+.*\s---[\r\n]+)@simU', '', (string)file_get_contents($filename)),
            basename($filename)
        );
    }
}
