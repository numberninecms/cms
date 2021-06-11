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
use Exception;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\ContentEntityTerm;
use NumberNine\Entity\Term;
use NumberNine\Entity\User;
use NumberNine\Event\UpdateContentEntityEvent;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Content\ContentType;
use NumberNine\Model\Content\PublishingStatusInterface;
use NumberNine\Repository\MediaFileRepository;
use NumberNine\Repository\TermRepository;
use NumberNine\Security\Capabilities;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     "content_entities/{type}/{id<\d+>}/",
 *     name="numbernine_admin_contententity_update_item",
 *     options={"expose"=true},
 *     methods={"PUT"}
 * )
 */
final class ContentEntityUpdateAction extends AbstractController implements AdminController
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param MediaFileRepository $mediaFileRepository
     * @param TermRepository $termRepository
     * @param ResponseFactory $responseFactory
     * @param ContentService $contentService
     * @param Serializer $serializer
     * @param ContentEntity $entity
     * @param string $type
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        MediaFileRepository $mediaFileRepository,
        TermRepository $termRepository,
        ResponseFactory $responseFactory,
        ContentService $contentService,
        SerializerInterface $serializer,
        ContentEntity $entity,
        string $type
    ): JsonResponse {
        $contentType = $contentService->getContentType($entity->getType());
        $this->validateAccess($entity, $contentType);

        /** @var array $data */
        $data = $request->request->all();
        $contentType = $contentService->getContentType($type);

        $serializer->denormalize(
            $data,
            $contentType->getEntityClassName(),
            null,
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => $entity,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'author',
                    'featuredImage',
                    'contentEntityTerms',
                    'children',
                    'parents',
                ]
            ]
        );

        $entity
            ->setSeoDescription($data['seoDescription'] ?? null)
            ->setCustomFields($data['customFields'] ?? null);

        if (
            !empty($data['featuredImage'])
            && method_exists($entity, 'getFeaturedImage')
            && method_exists($entity, 'setFeaturedImage')
            && $entity->getFeaturedImage()->getId() !== (int)$data['featuredImage']['id']
        ) {
            $featuredImage = $mediaFileRepository->find($data['featuredImage']['id']);
            $entity->setFeaturedImage($featuredImage);
        }

        if (!empty($data['status']) && $data['status'] === PublishingStatusInterface::STATUS_PUBLISH) {
            if ($this->isGranted($contentType->getMappedCapability(Capabilities::PUBLISH_POSTS))) {
                $entity->setStatus(PublishingStatusInterface::STATUS_PUBLISH);
            } else {
                $entity->setStatus(PublishingStatusInterface::STATUS_PENDING_REVIEW);
            }
        } else {
            $entity->setStatus($data['status']);
        }

        $submittedTermIds = array_map(static fn($term) => $term['id'], $data['terms'] ?? []);
        $existingTermIds = $entity->getContentEntityTerms()->map(
            fn(ContentEntityTerm $cet) => $cet->getTerm() instanceof Term ? $cet->getTerm()->getId() : null
        )->toArray();

        $termIdsToDelete = array_diff($existingTermIds, $submittedTermIds);
        $termIdsToAdd = array_diff($submittedTermIds, $existingTermIds);

        foreach ($termIdsToDelete as $id) {
            $cet = $entity->getContentEntityTerms()->filter(
                fn(ContentEntityTerm $cet) => $cet->getTerm() instanceof Term && $cet->getTerm()->getId() === $id
            )->first();
            $entity->removeContentEntityTerm($cet);
        }

        foreach ($termIdsToAdd as $id) {
            $term = $termRepository->find($id);

            if (!$term) {
                return $responseFactory->createErrorJsonResponse("Term with ID $id doesn't exist");
            }

            $cet = (new ContentEntityTerm())
                ->setContentEntity($entity)
                ->setTerm($term);

            $entity->addContentEntityTerm($cet);

            $entityManager->persist($cet);
        }

        $this->eventDispatcher->dispatch(new UpdateContentEntityEvent($entity, $request));

        try {
            $entityManager->flush();
        } catch (Exception $e) {
            return $responseFactory->createErrorJsonResponse($e->getMessage());
        }

        $context = [
            'groups' => [
                'content_entity_get_full',
                'media_file_get',
            ]
        ];

        return $responseFactory->createSerializedJsonResponse($entity, $context);
    }

    private function validateAccess(ContentEntity $entity, ContentType $contentType): void
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::EDIT_POSTS));

        if (
            $user instanceof User
            && $entity->getAuthor() instanceof User
            && $user->getId() !== $entity->getAuthor()->getId()
        ) {
            $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::EDIT_OTHERS_POSTS));
        }
    }
}
