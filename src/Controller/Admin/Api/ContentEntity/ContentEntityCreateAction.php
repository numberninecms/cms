<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Api\ContentEntity;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\ContentEntity;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "content_entities/{type}/new/",
 *     name="numbernine_admin_contententity_new_item",
 *     options={"expose"=true},
 *     methods={"GET"},
 *     priority="50"
 * )
 */
final class ContentEntityCreateAction extends AbstractController implements AdminController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ContentService $contentService
     * @param ResponseFactory $responseFactory
     * @param string $type
     * @return Response
     */
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        ContentService $contentService,
        ResponseFactory $responseFactory,
        string $type
    ): Response {
        $contentType = $contentService->getContentType($type);
        $class = $contentType->getEntityClassName();

        /** @var ContentEntity $entity */
        $entity = (new $class())
            ->setCustomType($type)
            ->setAuthor($this->getUser())
            ->setTitle(sprintf('New %s', $contentType->getLabels()->getSingularName()));

        $entityManager->persist($entity);
        $entityManager->flush();

        return $this->forward(
            ContentEntityGetAction::class,
            [
                'type' => $type,
                'id' => $entity->getId()
            ]
        );
    }
}
