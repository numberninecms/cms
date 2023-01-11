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

use NumberNine\Content\ArrayToShortcodeConverter;
use NumberNine\Content\ShortcodeMarkupBeautifier;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'page_builder/shortcodes/', name: 'numbernine_admin_page_builder_generate_shortcode', options: ['expose' => true], methods: [
    'POST',
])]
final class PageBuilderComponentToShortcodeAction implements AdminController
{
    public function __invoke(
        Request $request,
        ResponseFactory $responseFactory,
        ArrayToShortcodeConverter $arrayToShortcodeConverter,
        ShortcodeMarkupBeautifier $shortcodeMarkupBeautifier
    ): JsonResponse {
        $text = $arrayToShortcodeConverter->convertMany(
            [$request->request->all('component')],
            (bool) $request->request->get('beautify', false),
        );

        return $responseFactory->createSerializedJsonResponse($text);
    }
}
