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

use NumberNine\Model\Admin\AdminController;
use NumberNine\Content\ArrayToShortcodeConverter;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("page_builder/shortcodes/", name="numbernine_admin_page_builder_generate_shortcode", options={"expose"=true}, methods={"POST"})
 */
final class PageBuilderComponentToShortcodeAction implements AdminController
{
    public function __invoke(Request $request, ResponseFactory $responseFactory, ArrayToShortcodeConverter $arrayToShortcodeConverter): JsonResponse
    {
        $text = $arrayToShortcodeConverter->convertMany([$request->request->get('component')]);

        return $responseFactory->createSerializedJsonResponse($text);
    }
}
