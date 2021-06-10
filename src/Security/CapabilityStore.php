<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Security;

use NumberNine\Content\ContentService;
use NumberNine\Event\CapabilitiesListEvent;
use NumberNine\Model\Content\ContentType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class CapabilityStore
{
    private CapabilityGenerator $capabilityGenerator;
    private ContentService $contentService;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        CapabilityGenerator $capabilityGenerator,
        ContentService $contentService,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->capabilityGenerator = $capabilityGenerator;
        $this->contentService = $contentService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return string[]
     */
    public function getAllAvailableCapabilities(): array
    {
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
            ...$this->capabilityGenerator->generateMappedEditorCapabilities('post'),
            ...$this->capabilityGenerator->generateMappedEditorCapabilities('page'),
            ...$this->capabilityGenerator->generateMappedEditorCapabilities('block'),
            ...$this->capabilityGenerator->generateMappedEditorCapabilities('media_file'),
        ];

        $newCapabilities = array_map(
            fn (ContentType $contentType) => array_values($contentType->getCapabilities()),
            $this->contentService->getContentTypes()
        );

        $capabilities = array_unique(array_merge($capabilities, ...$newCapabilities));

        /** @var CapabilitiesListEvent $capabilitiesListEvent */
        $capabilitiesListEvent = $this->eventDispatcher->dispatch(new CapabilitiesListEvent($capabilities));

        return $capabilitiesListEvent->getCapabilities();
    }
}
