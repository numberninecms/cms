<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Theme;

use NumberNine\Exception\ThemeNotFoundException;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Theme\ThemeStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appearance/themes/{name}/screenshot", name="admin_appearance_themes_screenshot")
 */
final class ScreenshotAction extends AbstractController implements AdminController
{
    public function __invoke(ThemeStore $themeStore, string $name): Response
    {
        $themeWrapper = $themeStore->getTheme($name);

        if (!$themeWrapper) {
            throw new ThemeNotFoundException($name);
        }

        $theme = $themeWrapper->getTheme();

        ob_start();
        readfile($theme->getRootPath() . '/Resources/assets/screenshot.png');
        $result = ob_get_clean();

        return new Response((string)$result, 200, ['Content-Type' => 'image/png']);
    }
}
