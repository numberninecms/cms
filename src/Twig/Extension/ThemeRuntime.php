<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Extension;

use Exception;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\Term;
use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Model\General\Settings;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Repository\ContentEntityRepository;
use NumberNine\Content\ComponentRenderer;
use NumberNine\Content\PermalinkGenerator;
use NumberNine\Content\ShortcodeProcessor;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Http\RequestAnalyzer;
use NumberNine\Theme\TemplateResolver;
use NumberNine\Theme\ThemeOptionsReadWriter;
use NumberNine\Theme\ThemeStore;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Inflector\EnglishInflector;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\RuntimeExtensionInterface;

use function NumberNine\Util\ArrayUtil\array_implode_associative;

final class ThemeRuntime implements RuntimeExtensionInterface
{
    private ContentEntityRepository $contentEntityRepository;
    private ThemeStore $themeStore;
    private ThemeOptionsReadWriter $themeOptionsReadWriter;
    private ConfigurationReadWriter $configurationReadWriter;
    private ComponentRenderer $componentRenderer;
    private PermalinkGenerator $permalinkGenerator;
    private TemplateResolver $templateResolver;
    private ShortcodeProcessor $shortcodeProcessor;
    private ?Request $request;
    private RequestAnalyzer $requestAnalyzer;
    private UrlGeneratorInterface $urlGenerator;
    private EnglishInflector $inflector;

    public function __construct(
        ContentEntityRepository $contentEntityRepository,
        ThemeStore $themeStore,
        ThemeOptionsReadWriter $themeOptionsReadWriter,
        ConfigurationReadWriter $configurationReadWriter,
        ComponentRenderer $componentRenderer,
        PermalinkGenerator $permalinkGenerator,
        TemplateResolver $templateResolver,
        ShortcodeProcessor $shortcodeProcessor,
        RequestStack $requestStack,
        RequestAnalyzer $requestAnalyzer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->contentEntityRepository = $contentEntityRepository;
        $this->themeStore = $themeStore;
        $this->themeOptionsReadWriter = $themeOptionsReadWriter;
        $this->configurationReadWriter = $configurationReadWriter;
        $this->componentRenderer = $componentRenderer;
        $this->permalinkGenerator = $permalinkGenerator;
        $this->templateResolver = $templateResolver;
        $this->shortcodeProcessor = $shortcodeProcessor;
        $this->request = $requestStack->getMasterRequest();
        $this->requestAnalyzer = $requestAnalyzer;
        $this->urlGenerator = $urlGenerator;
        $this->inflector = new EnglishInflector();
    }

    /**
     * @return ThemeInterface
     * @throws ThemeNotFoundException
     */
    public function getCurrentTheme(): ThemeInterface
    {
        return $this->themeStore->getCurrentTheme();
    }

    /**
     * @param string $areaName
     * @return string
     * @throws Exception
     */
    public function renderArea(string $areaName): string
    {
        if ($this->request && $this->requestAnalyzer->isPreviewMode() && $this->request->get('area') === $areaName) {
            return '<page-builder></page-builder>';
        }

        $areaContent = $this->themeOptionsReadWriter->read($this->themeStore->getCurrentTheme(), 'areas', [])[$areaName] ?? '';

        return $this->shortcodeProcessor->applyShortcodes($areaContent);
    }

    /**
     * @param string $componentName
     * @param array $args
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderComponent(string $componentName, array $args = []): string
    {
        return $this->componentRenderer->renderComponent($componentName, $args);
    }

    /**
     * @param string $optionName
     * @return mixed|null
     * @throws ThemeNotFoundException
     */
    public function getThemeOption(string $optionName)
    {
        return $this->themeOptionsReadWriter->read($this->themeStore->getCurrentTheme(), $optionName, null, false, $this->requestAnalyzer->isPreviewMode());
    }

    /**
     * @param string $settingName
     * @param null $default
     * @return mixed|null
     */
    public function getSetting(string $settingName, $default = null)
    {
        $settings = [$settingName => $this->configurationReadWriter->read($settingName, $default)];

        if ($this->requestAnalyzer->isPreviewMode()) {
            $settings = array_merge(
                $settings,
                $this->configurationReadWriter->read(Settings::CUSTOMIZER_DRAFT_SETTINGS, [])
            );
        }

        return $settings[$settingName] ?? null;
    }

    /**
     * @param ContentEntity $contentEntity
     * @return string
     */
    public function getEntityUrl(ContentEntity $contentEntity): string
    {
        return $this->permalinkGenerator->generateContentEntityPermalink($contentEntity);
    }

    /**
     * @param ContentEntity $contentEntity
     * @param array $attributes
     * @param string|null $linkText
     * @return string
     */
    public function getEntityLink(ContentEntity $contentEntity, array $attributes = [], string $linkText = null): string
    {
        $attributesAsString = array_implode_associative($attributes, ' ', '=', '', '"');

        return sprintf(
            '<a href="%s"%s>%s</a>',
            $this->getEntityUrl($contentEntity),
            $attributesAsString ? ' ' . $attributesAsString : '',
            $linkText ?? $contentEntity->getTitle()
        );
    }

    /**
     * @param ContentEntity $contentEntity
     * @return string
     */
    public function getEntityAdminUrl(ContentEntity $contentEntity): string
    {
        return sprintf(
            '%s#/%s/%d/',
            $this->urlGenerator->generate('numbernine_admin_index'),
            current($this->inflector->pluralize($contentEntity->getType())),
            $contentEntity->getId()
        );
    }

    /**
     * @param ContentEntity $contentEntity
     * @param string $taxonomyName
     * @param string $separator
     * @param array $attributes
     * @return string
     */
    public function getTermsLinkList(ContentEntity $contentEntity, string $taxonomyName, string $separator = ', ', array $attributes = []): string
    {
        return implode($separator, $this->getTermsLinkArray($contentEntity, $taxonomyName, $attributes));
    }

    /**
     * @param ContentEntity $contentEntity
     * @param string $taxonomyName
     * @param array $attributes
     * @return array
     */
    private function getTermsLinkArray(ContentEntity $contentEntity, string $taxonomyName, array $attributes = []): array
    {
        return array_map(fn(Term $term) => $this->getTermLink($term, $attributes), $contentEntity->getTerms($taxonomyName));
    }

    /**
     * @param Term $term
     * @param array $attributes
     * @return string
     */
    public function getTermLink(Term $term, array $attributes = []): string
    {
        return sprintf(
            '<a href="%s"%s>%s</a>',
            $this->permalinkGenerator->generateTermPermalink($term),
            empty($attributes) ? '' : ' ' . array_implode_associative($attributes, ' ', '=', '', '"'),
            $term->getName()
        );
    }

    public function getBaseTemplate(): string
    {
        return $this->templateResolver->resolveBaseLayout();
    }

    public function getPath(string $settingName): string
    {
        if (!($id = (int)$this->configurationReadWriter->read($settingName))) {
            return '';
        }

        $entity = $this->contentEntityRepository->find($id);

        return $entity ? $this->permalinkGenerator->generateContentEntityPermalink($entity) : '';
    }

    public function getCurrentRoutePagePath(int $page): string
    {
        if (!$this->request) {
            return '';
        }

        $route = $this->request->attributes->get('_route');
        $params = $this->request->attributes->get('_route_params');
        $queryString = $this->request->getQueryString();

        if ($page > 1) {
            $route .= substr($route, -5) === '_page' ? '' : '_page';
            $params['page'] = $page;
        } else {
            $route = preg_replace('/_page$/', '', $route);
            unset($params['page']);
        }

        return $this->urlGenerator->generate($route, $params) . ($queryString ? '?' . $queryString : '');
    }

    public function isHomepage(): bool
    {
        return $this->requestAnalyzer->isHomePage();
    }
}
