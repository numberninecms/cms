<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\PageBuilder;

use NumberNine\Security\Capabilities;
use NumberNine\Theme\ThemeOptionsReadWriter;
use NumberNine\Theme\ThemeStore;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'page_builder/{area<(?!\d+)[\w_\-]+>}/components', name: 'numbernine_admin_pagebuilder_area_get_components', options: ['expose' => true], methods: [
    'GET',
])]
final class PageBuilderAreaComponentsGetAction extends AbstractPageBuilderGetAction
{
    public function __invoke(
        ThemeStore $themeStore,
        ThemeOptionsReadWriter $themeOptionsReadWriter,
        string $area
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::CUSTOMIZE);

        $area = $themeOptionsReadWriter->read($themeStore->getCurrentTheme(), 'areas', [])[$area] ?? '';

        return $this->createPageBuilderResponseFromText($area);
    }
}
