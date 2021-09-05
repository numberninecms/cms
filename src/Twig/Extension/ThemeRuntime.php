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
use NumberNine\Content\ContentService;
use NumberNine\Content\ShortcodeRenderer;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\Term;
use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Model\General\Settings;
use NumberNine\Model\Theme\ThemeInterface;
use NumberNine\Repository\ContentEntityRepository;
use NumberNine\Content\ComponentRenderer;
use NumberNine\Content\PermalinkGenerator;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Http\RequestAnalyzer;
use NumberNine\Theme\TemplateResolver;
use NumberNine\Theme\ThemeOptionsReadWriter;
use NumberNine\Theme\ThemeStore;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\RuntimeExtensionInterface;

use function NumberNine\Common\Util\ArrayUtil\array_implode_associative;

final class ThemeRuntime implements RuntimeExtensionInterface
{
    private ?Request $request;

    public function __construct(
        private ContentEntityRepository $contentEntityRepository,
        private ThemeStore $themeStore,
        private ThemeOptionsReadWriter $themeOptionsReadWriter,
        private ConfigurationReadWriter $configurationReadWriter,
        private ComponentRenderer $componentRenderer,
        private PermalinkGenerator $permalinkGenerator,
        private TemplateResolver $templateResolver,
        private ShortcodeRenderer $shortcodeRenderer,
        RequestStack $requestStack,
        private RequestAnalyzer $requestAnalyzer,
        private UrlGeneratorInterface $urlGenerator,
        private ContentService $contentService,
        private SluggerInterface $slugger
    ) {
        $this->request = $requestStack->getMasterRequest();
    }

    public function getCurrentTheme(): ThemeInterface
    {
        return $this->themeStore->getCurrentTheme();
    }

    public function renderArea(string $areaName): string
    {
        if ($this->request && $this->requestAnalyzer->isPreviewMode() && $this->request->get('area') === $areaName) {
            return '<page-builder></page-builder>';
        }

        $areaContent = $this->themeOptionsReadWriter->read(
            $this->themeStore->getCurrentTheme(),
            'areas',
            []
        )[$areaName] ?? '';

        return $this->shortcodeRenderer->applyShortcodes($areaContent);
    }

    public function renderComponent(string $componentName, array $args = []): string
    {
        return $this->componentRenderer->renderComponent($componentName, $args);
    }

    /**
     * @return mixed|null
     * @throws ThemeNotFoundException
     */
    public function getThemeOption(string $optionName)
    {
        return $this->themeOptionsReadWriter->read(
            $this->themeStore->getCurrentTheme(),
            $optionName,
            null,
            false,
            $this->requestAnalyzer->isPreviewMode()
        );
    }

    /**
     * @param mixed $default
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

    public function getEntityUrl(ContentEntity $contentEntity): string
    {
        return $this->permalinkGenerator->generateContentEntityPermalink($contentEntity);
    }

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

    public function getEntityAdminUrl(ContentEntity $contentEntity): string
    {
        return $this->urlGenerator->generate('numbernine_admin_content_entity_edit', [
            'type' => $this->slugger->slug(
                (string)$this->contentService->getContentType($contentEntity->getType())->getLabels()->getPluralName(),
            ),
            'id' => $contentEntity->getId(),
        ]);
    }

    public function getTermsLinkList(
        ContentEntity $contentEntity,
        string $taxonomyName,
        string $separator = ', ',
        array $attributes = []
    ): string {
        return implode($separator, $this->getTermsLinkArray($contentEntity, $taxonomyName, $attributes));
    }

    private function getTermsLinkArray(
        ContentEntity $contentEntity,
        string $taxonomyName,
        array $attributes = []
    ): array {
        return array_map(
            fn(Term $term): string => $this->getTermLink($term, $attributes),
            $contentEntity->getTerms($taxonomyName)
        );
    }

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

    public function getPath(string $settingName, int $page = 1, bool $absolute = false): string
    {
        if (!($id = (int)$this->configurationReadWriter->read($settingName))) {
            return '';
        }

        $entity = $this->contentEntityRepository->find($id);

        return $entity ? $this->permalinkGenerator->generateContentEntityPermalink($entity, $page, $absolute) : '';
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
            $route .= str_ends_with($route, '_page') ? '' : '_page';
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
