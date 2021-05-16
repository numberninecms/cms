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

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Content\ContentService;
use NumberNine\Entity\ContentEntity;
use NumberNine\Form\Admin\Content\AdminContentEntityFormType;
use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/{type}/{id<\d+>}/", name="numbernine_admin_content_entity_edit", methods={"GET", "POST"})
 */
final class ContentEntityEditAction extends AbstractController implements AdminController
{
    public function __invoke(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ContentService $contentService,
        Request $request,
        ContentEntity $entity,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);

        $form = $this->createForm(AdminContentEntityFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', sprintf(
                    "%s '%s' successfully saved.",
                    ucfirst((string)$contentType->getLabels()->getSingularName()),
                    $entity->getTitle(),
                ));

                return $this->redirectToRoute('numbernine_admin_content_entity_edit', [
                    'type' => $type,
                    'id' => $entity->getId(),
                ], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', sprintf(
                    "Unable to save %s '%s'.",
                    $contentType->getLabels()->getSingularName(),
                    $entity->getTitle(),
                ));
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/content_entity/edit.html.twig', [
            'content_type' => $contentType,
            'type_slug' => $type,
            'entity' => $entity,
            'form' => $form->createView(),
        ], $response);
    }
}
