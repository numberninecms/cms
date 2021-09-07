<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\ContentEntity;

use Exception;
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

#[Route(path: '/{type}/', name: 'numbernine_admin_content_entity_index', methods: [
    'GET',
    'POST',
], priority: '-1000')]
final class ContentEntityIndexAction extends AbstractController implements AdminController
{
    public function __invoke(
        SerializerInterface $serializer,
        ContentService $contentService,
        Request $request,
        LoggerInterface $logger,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);

        /** @var PaginationParameters $paginationParameters */
        $paginationParameters = $serializer->denormalize(
            $request->query->all(),
            PaginationParameters::class,
            null,
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $entities = $contentService->getEntitiesOfType($type, $paginationParameters);
        $isTrash = $paginationParameters->getStatus() === 'deleted';

        /** @var Form $form */
        $form = $this->createForm(AdminContentEntityIndexFormType::class, null, [
            'entities' => $entities,
            'trash' => $isTrash,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkedIds = array_map(
                fn ($name): int => (int) str_replace('entity_', '', $name),
                array_keys(array_filter($form->getData()))
            );

            try {
                if ($isTrash && $form->getClickedButton()->getName() === 'restore') { // @phpstan-ignore-line
                    $contentService->restoreEntitiesOfType($contentType->getName(), $checkedIds);
                    $this->addFlash('success', sprintf(
                        '%s have been successfully restored.',
                        ucfirst((string) $contentType->getLabels()->getPluralName()),
                    ));
                } else {
                    $contentService->deleteEntitiesOfType($contentType->getName(), $checkedIds);

                    if ($isTrash) {
                        $this->addFlash('success', sprintf(
                            '%s have been permanently deleted.',
                            ucfirst((string) $contentType->getLabels()->getPluralName()),
                        ));
                    } else {
                        $this->addFlash('success', sprintf(
                            '%s have been deleted and placed in trash.',
                            ucfirst((string) $contentType->getLabels()->getPluralName()),
                        ));
                    }
                }

                return $this->redirectToRoute('numbernine_admin_content_entity_index', array_merge(
                    ['type' => $type],
                    $request->query->all(),
                ), Response::HTTP_SEE_OTHER);
            } catch (Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'An unknown error occured.');
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/content_entity/index.html.twig', [
            'content_type' => $contentType,
            'type_slug' => $type,
            'entities' => $entities,
            'form' => $form->createView(),
            'is_trash' => $isTrash,
        ], $response);
    }
}
