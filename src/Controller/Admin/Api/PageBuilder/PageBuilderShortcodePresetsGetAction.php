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
use NumberNine\Content\ShortcodeProcessor;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Annotation\Route(path: 'page_builder/shortcodes/{name}/presets/', name: 'numbernine_admin_page_builder_shortcode_get_presets', options: ['expose' => true], methods: ['GET'])]
final class PageBuilderShortcodePresetsGetAction implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        ShortcodeProcessor $shortcodeProcessor,
        string $name
    ): JsonResponse {
        $associativePresets = $shortcodeProcessor->getShortcodePresets($name);

        $presets = array_map(
            fn($v, $k): array => [
                'name' => $k,
                'components' => $shortcodeProcessor->buildShortcodeTree($v, true, false, true)
            ],
            $associativePresets,
            array_keys($associativePresets)
        );

        return $responseFactory->createSerializedJsonResponse($presets);
    }
}
