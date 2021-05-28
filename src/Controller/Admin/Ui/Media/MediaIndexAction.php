<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\Media;

use NumberNine\Content\ContentService;
use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/media/", name="numbernine_admin_media_library_index", methods={"GET"})
 */
final class MediaIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        ContentService $contentService,
        SerializerInterface $serializer
    ): Response {
        return $this->render('@NumberNine/admin/media/index.html.twig');
    }
}
