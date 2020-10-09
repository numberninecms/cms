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

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Content\ArrayToShortcodeConverter;
use NumberNine\Http\ResponseFactory;
use NumberNine\Theme\ThemeOptionsReadWriter;
use NumberNine\Theme\ThemeStore;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     path="page_builder/{area<(?!\d+)[\w_\-]+>}/components",
 *     name="numbernine_admin_pagebuilder_post_area_components",
 *     options={"expose"=true}, methods={"POST"}
 * )
 */
final class PageBuilderAreaComponentsUpdateAction
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        ResponseFactory $responseFactory,
        ArrayToShortcodeConverter $arrayToShortcodeConverter,
        ThemeStore $themeStore,
        ThemeOptionsReadWriter $themeOptionsReadWriter,
        string $area
    ): JsonResponse {
        $text = $arrayToShortcodeConverter->convertMany($request->request->get('components'));

        $areas = $themeOptionsReadWriter->read($themeStore->getCurrentTheme(), 'areas', []);
        $areas[$area] = $text;

        $themeOptionsReadWriter->write($themeStore->getCurrentTheme(), 'areas', $areas, false, true);

        return $responseFactory->createSuccessJsonResponse();
    }
}
