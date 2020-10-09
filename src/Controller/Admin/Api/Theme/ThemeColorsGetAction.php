<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\Theme;

use NumberNine\Model\Admin\AdminController;
use NumberNine\Http\ResponseFactory;
use NumberNine\Theme\ThemeOptionsReadWriter;
use NumberNine\Theme\ThemeStore;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("theme/colors/", name="numbernine_admin_theme_colors_get_collection", options={"expose"=true}, methods={"GET"})
 */
final class ThemeColorsGetAction implements AdminController
{
    public function __invoke(Request $request, ResponseFactory $responseFactory, ThemeStore $themeStore, ThemeOptionsReadWriter $themeOptionsReadWriter): JsonResponse
    {
        $themeOptions = $themeOptionsReadWriter->readAll($themeStore->getCurrentTheme(), false, true);

        // The need to reorder the colors is due to a feature of MySQL which automatically sorts keys in JSON columns.
        // The color picker needs them to be ordered.
        $order = ['primary', 'secondary', 'tertiary', 'quaternary', 'success', 'dark', 'light'];
        $colors = array_map(
            static function ($value, $color) {
                return [
                    'name' => $color,
                    'value' => $value,
                ];
            },
            $themeOptions['colors'] ?? [],
            array_keys($themeOptions['colors']) ?? []
        );

        usort(
            $colors,
            static function ($a, $b) use ($order) {
                $aIndex = array_search($a['name'], $order, true);
                $bIndex = array_search($b['name'], $order, true);

                if ($aIndex === false) {
                    return 1;
                }

                if ($bIndex === false) {
                    return -1;
                }

                return $aIndex <=> $bIndex;
            }
        );

        return $responseFactory->createSerializedJsonResponse($colors);
    }
}
