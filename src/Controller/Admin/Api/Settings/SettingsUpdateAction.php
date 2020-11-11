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
use NumberNine\Model\Admin\AdminController;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("settings/", name="numbernine_admin_settings_update_collection", options={"expose"=true}, methods={"PUT"})
 */
final class SettingsUpdateAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        ResponseFactory $responseFactory,
        ConfigurationReadWriter $configurationReadWriter
    ): JsonResponse {
        $settings = $request->request->all();

        if (!empty($settings)) {
            $configurationReadWriter->writeMany((array)array_combine(
                array_column($settings, 'name'),
                array_column($settings, 'value')
            ));
        }

        return $responseFactory->createSuccessJsonResponse();
    }
}
