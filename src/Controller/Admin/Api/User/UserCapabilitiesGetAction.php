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
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/users/capabilities/",
 *     name="numbernine_admin_user_capabilities_get_collection",
 *     options={"expose"=true},
 *     methods={"GET"}
 * )
 */
final class UserCapabilitiesGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        ResponseFactory $responseFactory,
        EventDispatcherInterface $eventDispatcher,
        CapabilityGenerator $capabilityGenerator,
        ContentService $contentService
    ): JsonResponse {
        $this->denyAccessUnlessGranted(Capabilities::MANAGE_ROLES);

        $capabilities = [
            Capabilities::READ,
            Capabilities::ACCESS_ADMIN,
            Capabilities::UPLOAD_FILES,
            Capabilities::MANAGE_CATEGORIES,
            Capabilities::MODERATE_COMMENTS,
            Capabilities::MANAGE_OPTIONS,
            Capabilities::LIST_USERS,
            Capabilities::PROMOTE_USERS,
            Capabilities::REMOVE_USERS,
            Capabilities::EDIT_USERS,
            Capabilities::ADD_USERS,
            Capabilities::CREATE_USERS,
            Capabilities::DELETE_USERS,
            Capabilities::MANAGE_ROLES,
            Capabilities::CUSTOMIZE,
            ...$capabilityGenerator->generateMappedEditorCapabilities('post'),
            ...$capabilityGenerator->generateMappedEditorCapabilities('page'),
            ...$capabilityGenerator->generateMappedEditorCapabilities('block'),
            ...$capabilityGenerator->generateMappedEditorCapabilities('media_file'),
        ];

        $newCapabilities = array_map(
            fn (ContentType $contentType) => array_values($contentType->getCapabilities()),
            $contentService->getContentTypes()
        );

        $capabilities = array_unique(array_merge($capabilities, ...$newCapabilities));

        /** @var CapabilitiesListEvent $capabilitiesListEvent */
        $capabilitiesListEvent = $eventDispatcher->dispatch(new CapabilitiesListEvent($capabilities));

        return $responseFactory->createSerializedJsonResponse($capabilitiesListEvent->getCapabilities());
    }
}
