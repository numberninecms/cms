<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\Area;

use NumberNine\Model\Admin\AdminController;
use NumberNine\Security\Capabilities;
use NumberNine\Http\ResponseFactory;
use NumberNine\Content\AreaStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/areas/", name="numbernine_admin_areas_get_collection", options={"expose"=true}, methods={"GET"})
 */
final class AreasGetAction extends AbstractController implements AdminController
{
    public function __invoke(ResponseFactory $responseFactory, AreaStore $areaStore): JsonResponse
    {
        $this->denyAccessUnlessGranted(Capabilities::CUSTOMIZE);

        $areas = $areaStore->getAreas();
        asort($areas);
        $areas = array_map(fn($v, $k) => ['id' => $k, 'name' => $v], $areas, array_keys($areas));

        return $responseFactory->createSerializedJsonResponse($areas);
    }
}
