<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\Media;

use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use function NumberNine\Common\Util\ConfigUtil\get_file_upload_max_size;

/**
 * @Route(
 *     "/media_files/options",
 *     name="numbernine_admin_media_files_get_options",
 *     options={"expose"=true},
 *     methods={"GET"}
 * )
 */
final class MediaLibraryOptionsGetAction
{
    public function __invoke(ResponseFactory $responseFactory): JsonResponse
    {
        return $responseFactory->createSerializedJsonResponse(
            [
                'max_upload_size' => get_file_upload_max_size()
            ]
        );
    }
}
