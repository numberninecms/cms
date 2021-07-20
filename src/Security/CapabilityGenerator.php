<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Security;

use NumberNine\Security\Capabilities;
use NumberNine\Content\ContentService;

final class CapabilityGenerator
{
    private ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function generateMappedSubscriberCapabilities(string $contentType): array
    {
        return $this->contentService->getContentType($contentType)->getMappedCapabilities(
            [
                Capabilities::READ_POSTS,
            ]
        );
    }

    public function generateMappedContributorCapabilities(string $contentType): array
    {
        return $this->contentService->getContentType($contentType)->getMappedCapabilities(
            [
                ...$this->generateMappedSubscriberCapabilities($contentType),
                Capabilities::EDIT_POSTS,
                Capabilities::DELETE_POSTS,
            ]
        );
    }

    public function generateMappedAuthorCapabilities(string $contentType): array
    {
        return $this->contentService->getContentType($contentType)->getMappedCapabilities(
            [
                ...$this->generateMappedContributorCapabilities($contentType),
                Capabilities::PUBLISH_POSTS,
                Capabilities::EDIT_PUBLISHED_POSTS,
                Capabilities::DELETE_PUBLISHED_POSTS,
            ]
        );
    }

    public function generateMappedEditorCapabilities(string $contentType): array
    {
        return $this->contentService->getContentType($contentType)->getMappedCapabilities(
            [
                ...$this->generateMappedAuthorCapabilities($contentType),
                Capabilities::READ_PRIVATE_POSTS,
                Capabilities::EDIT_OTHERS_POSTS,
                Capabilities::EDIT_PRIVATE_POSTS,
                Capabilities::DELETE_OTHERS_POSTS,
                Capabilities::DELETE_PRIVATE_POSTS,
            ]
        );
    }
}
