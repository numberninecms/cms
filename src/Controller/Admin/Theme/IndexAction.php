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

use NumberNine\Model\Admin\AdminController;
use NumberNine\Theme\ThemeStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appearance/themes", name="admin_appearance_themes")
 */
final class IndexAction extends AbstractController implements AdminController
{
    public function __invoke(ThemeStore $themeStore): Response
    {
        $themes = $themeStore->getThemes();
        $currentTheme = $themeStore->getCurrentTheme();

        return $this->render(
            '@NumberNine/admin/theme/index.html.twig',
            [
                'themes' => $themes,
                'currentTheme' => $currentTheme,
            ]
        );
    }
}
