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

use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\General\Settings;
use NumberNine\Model\General\SettingsDefaultValues;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: 'settings/', name: 'numbernine_admin_settings_get_collection', options: ['expose' => true], methods: [
    'GET',
])]
final class SettingsGetAction extends AbstractController implements AdminController
{
    public function __invoke(
        UrlGeneratorInterface $urlGenerator,
        ResponseFactory $responseFactory,
        ConfigurationReadWriter $configurationReadWriter,
        ContentService $contentService
    ): JsonResponse {
        $defaultPermalinks = [];

        foreach ($contentService->getContentTypes() as $contentType) {
            $defaultPermalinks[$contentType->getName()] = $contentType->getPermalink();
        }

        $settings = $configurationReadWriter->readMany(
            [
                Settings::SITE_TITLE => SettingsDefaultValues::SITE_TITLE,
                Settings::SITE_DESCRIPTION => SettingsDefaultValues::SITE_DESCRIPTION,
                Settings::PAGE_FOR_FRONT,
                Settings::PAGE_FOR_POSTS,
                Settings::PAGE_FOR_PRIVACY,
                Settings::ROOT_ABSOLUTE_URL => $urlGenerator->generate(
                    'numbernine_homepage',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                Settings::PERMALINKS => $defaultPermalinks,
                Settings::MAILER_SENDER_NAME => SettingsDefaultValues::MAILER_SENDER_NAME,
                Settings::MAILER_SENDER_ADDRESS => SettingsDefaultValues::MAILER_SENDER_ADDRESS,
            ],
            false
        );

        return $responseFactory->createSerializedJsonResponse($settings);
    }
}
