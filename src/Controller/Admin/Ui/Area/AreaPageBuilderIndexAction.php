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

use NumberNine\Content\AreaStore;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Security\Capabilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/area/", name="numbernine_admin_area_page_builder_index", methods={"GET"})
 */
final class AreaPageBuilderIndexAction extends AbstractController implements AdminController
{
    public function __invoke(AreaStore $areaStore): Response
    {
        $this->denyAccessUnlessGranted(Capabilities::CUSTOMIZE);

        return $this->render('@NumberNine/admin/area/index.html.twig', [
            'areas' => $areaStore->getAreas(),
        ]);
    }
}
