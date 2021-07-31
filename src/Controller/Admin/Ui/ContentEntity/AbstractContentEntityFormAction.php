<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Controller\Admin\Ui\ContentEntity;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\ContentEntity;
use NumberNine\Form\Admin\Content\AdminContentEntityEditFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Content\ContentType;
use NumberNine\Model\Content\EditorExtensionBuilder;
use NumberNine\Model\Content\EditorExtensionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

abstract class AbstractContentEntityFormAction extends AbstractController implements AdminController
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private SluggerInterface $slugger;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        SluggerInterface $slugger
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->slugger = $slugger;
    }

    protected function handle(Request $request, ContentType $contentType, ContentEntity $entity): Response
    {
        $editorExtensions = $this->getEditorExtensions($contentType);
        $type = $this->slugger->slug((string)$contentType->getLabels()->getPluralName());
        $form = $this->createForm(AdminContentEntityEditFormType::class, $entity, [
            'editor_extensions' => $editorExtensions,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);

            try {
                $this->entityManager->flush();

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
                $this->logger->error($e->getMessage());
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
            'editor_extensions' => $editorExtensions,
        ], $response);
    }

    private function getEditorExtensions(ContentType $contentType): array
    {
        if (!($extensionClassName = $contentType->getEditorExtension())) {
            return [];
        }

        /** @var EditorExtensionInterface $extension */
        $extension = new $extensionClassName();
        $builder = new EditorExtensionBuilder();
        $extension->build($builder);

        return $builder->all();
    }
}
