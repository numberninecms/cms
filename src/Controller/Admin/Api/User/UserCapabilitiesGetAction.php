<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\User;

use NumberNine\Content\ContentService;
use NumberNine\Event\CapabilitiesListEvent;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Content\ContentType;
use NumberNine\Security\Capabilities;
use NumberNine\Http\ResponseFactory;
use NumberNine\Security\CapabilityGenerator;
use NumberNine\Security\CapabilityStore;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Annotation\Route(path: '/users/capabilities/', name: 'numbernine_admin_user_capabilities_get_collection', options: ['expose' => true], methods: ['GET'])]
final class UserCapabilitiesGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        CapabilityStore $capabilityStore
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::MANAGE_ROLES);

        return $responseFactory->createSerializedJsonResponse($capabilityStore->getAllAvailableCapabilities());
    }
}
