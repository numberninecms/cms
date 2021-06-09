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
use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/{type}/new/", name="numbernine_admin_content_entity_create", methods={"GET"}, priority="-1000")
 */
final class ContentEntityCreateAction extends AbstractController implements AdminController
{
    public function __invoke(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ContentService $contentService,
        Request $request,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);
        $class = $contentType->getEntityClassName();

        /** @var ContentEntity $entity */
        $entity = (new $class())
            ->setCustomType($contentType->getName())
            ->setAuthor($this->getUser())
            ->setTitle(sprintf('New %s', $contentType->getLabels()->getSingularName()));

        $entityManager->persist($entity);

        try {
            $entityManager->flush();

            return $this->redirectToRoute('numbernine_admin_content_entity_edit', [
                'type' => $type,
                'id' => $entity->getId(),
            ], Response::HTTP_SEE_OTHER);
        } catch (\Exception $e) {
            $this->addFlash('error', sprintf('Unable to create new %s', $contentType->getLabels()->getSingularName()));

            return $this->redirect((string)$request->headers->get('referer'));
        }
    }
}
