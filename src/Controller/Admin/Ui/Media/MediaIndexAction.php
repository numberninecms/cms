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
use NumberNine\Form\Admin\Content\AdminContentEntityIndexFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Pagination\PaginationParameters;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/media/", name="numbernine_admin_media_library_index", methods={"GET", "POST"})
 */
final class MediaIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        Request $request,
        ContentService $contentService,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ): Response {
        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            array_merge([
                'fetchCount' => 20,
                'orderBy' => 'updatedAt',
                'order' => 'desc',
                'status' => 'deleted',
            ], $request->query->all()),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $deletedMediaFiles = $contentService->getEntitiesOfType('media_file', $paginationParameters);

        /** @var Form $form */
        $form = $this->createForm(AdminContentEntityIndexFormType::class, null, [
            'entities' => $deletedMediaFiles,
            'trash' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkedIds = array_map(
                fn ($name) => (int)str_replace('entity_', '', $name),
                array_keys(array_filter($form->getData()))
            );

            try {
                if ($form->getClickedButton()->getName() === 'restore') { // @phpstan-ignore-line
                    $contentService->restoreEntitiesOfType('media_file', $checkedIds);
                    $this->addFlash('success', 'Media files have been successfully restored.');
                } else {
                    $contentService->deleteEntitiesOfType('media_file', $checkedIds);
                    $this->addFlash('success', 'Media files have been permanently deleted.');
                }

                return $this->redirectToRoute('numbernine_admin_media_library_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/media/index.html.twig', [
            'deleted_media_files' => $deletedMediaFiles,
            'form' => $form->createView(),
        ], $response);
    }
}
