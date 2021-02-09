<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\Settings;

use NumberNine\Content\ContentService;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\General\Settings;
use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("settings/", name="numbernine_admin_settings_get_collection", options={"expose"=true}, methods={"GET"})
 */
final class SettingsGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        UrlGeneratorInterface $urlGenerator,
        ResponseFactory $responseFactory,
        ConfigurationReadWriter $configurationReadWriter,
        ContentService $contentService
    ): JsonResponse {
        $settings = $configurationReadWriter->readMany(
            [
                Settings::PAGE_FOR_FRONT,
                Settings::PAGE_FOR_POSTS,
                Settings::PERMALINKS,
            ],
            false
        );

        $permalinks = $settings[2]['value'] ?? [];

        foreach ($contentService->getContentTypes() as $contentType) {
            if (!array_key_exists($contentType->getName(), $permalinks)) {
                $permalinks[$contentType->getName()] = $contentType->getPermalink();
            }
        }

        $settings[2]['value'] = $permalinks;

        $settings[] = [
            'name' => 'root_absolute_url',
            'value' => $urlGenerator->generate('numbernine_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ];

        return $responseFactory->createSerializedJsonResponse($settings);
    }
}
