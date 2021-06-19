<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\Area;

use NumberNine\Content\ContentService;
use NumberNine\Entity\ContentEntity;
use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/area/{area}/builder/", name="numbernine_admin_area_page_builder_edit", methods={"GET"})
 */
final class AreaPageBuilderEditAction extends AbstractController implements AdminController
{
    public function __invoke(
        string $area
    ): Response {
        return $this->render('@NumberNine/admin/area/builder_edit.html.twig', [
            'area' => $area,
        ]);
    }
}
