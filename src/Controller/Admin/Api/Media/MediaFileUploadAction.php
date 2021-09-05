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

use NumberNine\Media\MediaFileFactory;
use NumberNine\Http\ResponseFactory;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[\Symfony\Component\Routing\Annotation\Route(path: '/media_files/upload', name: 'numbernine_admin_media_files_upload', options: ['expose' => true], methods: ['POST'])]
final class MediaFileUploadAction
{
    public function __invoke(
        Request $request,
        MediaFileFactory $mediaFileFactory,
        ResponseFactory $responseFactory
    ): JsonResponse {
        /** @var UploadedFile|null $file */
        $file = $request->files->get('file');

        if ($file === null) {
            throw new RuntimeException('No file uploaded.');
        }

        return $responseFactory->createSerializedJsonResponse(
            $mediaFileFactory->createMediaFileFromUploadedFile($file)
        );
    }
}
