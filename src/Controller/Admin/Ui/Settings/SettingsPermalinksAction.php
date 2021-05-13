<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\Settings;

use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Content\ContentService;
use NumberNine\Form\Admin\Settings\AdminSettingsGeneralFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\General\Settings;
use NumberNine\Model\General\SettingsDefaultValues;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/settings/permalinks/", name="numbernine_admin_settings_permalinks", methods={"GET", "POST"})
 */
final class SettingsPermalinksAction extends AbstractController implements AdminController
{
    public function __invoke(
        UrlGeneratorInterface $urlGenerator,
        ConfigurationReadWriter $configurationReadWriter,
        ContentService $contentService,
        Request $request
    ): Response {
        $defaultPermalinks = [];

        foreach ($contentService->getContentTypes() as $contentType) {
            $defaultPermalinks[$contentType->getName()] = $contentType->getPermalink();
        }

        $settings = $configurationReadWriter->readMany([
            Settings::PERMALINKS => $defaultPermalinks
        ]);

        return $this->render('@NumberNine/admin/settings/permalinks.html.twig');
    }
}
