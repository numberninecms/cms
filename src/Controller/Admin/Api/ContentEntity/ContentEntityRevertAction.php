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
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\User;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Content\ContentType;
use NumberNine\Security\Capabilities;
use NumberNine\Content\ContentService;
use NumberNine\Http\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("content_entities/{type}/{id<\d+>}/revert/{version}/", name="numbernine_admin_contententity_revert_item", options={"expose"=true}, methods={"POST"})
 */
final class ContentEntityRevertAction extends AbstractController implements AdminController
{
    public function __invoke(
        EntityManagerInterface $entityManager,
        ResponseFactory $responseFactory,
        ContentService $contentService,
        ContentEntity $entity,
        string $version
    ): JsonResponse {
        $contentType = $contentService->getContentType($entity->getType());
        $this->validateAccess($entity, $contentType);

        /** @var LogEntryRepository $logEntryRepository */
        $logEntryRepository = $entityManager->getRepository(LogEntry::class);

        $logEntryRepository->revert($entity, intval($version));
        $entityManager->persist($entity);

        try {
            $entityManager->flush();
        } catch (Exception $e) {
            return $responseFactory->createErrorJsonResponse($e->getMessage());
        }

        $this->deleteNewerVersions($entityManager, $logEntryRepository, $entity, intval($version));

        $context = [
            'groups' => [
                'content_entity_get',
                'web_access_get',
                'author_get',
                'editor_get',
                'seo_get',
                'custom_fields_get',
                'featured_image_get',
                'media_file_get',
            ]
        ];

        return $responseFactory->createSerializedJsonResponse($entity, $context);
    }

    private function validateAccess(ContentEntity $entity, ContentType $contentType): void
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::EDIT_POSTS));

        if ($user instanceof User && $user->getId() !== $entity->getAuthor()->getId()) {
            $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::EDIT_OTHERS_POSTS));
        }
    }

    private function deleteNewerVersions(EntityManagerInterface $entityManager, LogEntryRepository $logEntryRepository, ContentEntity $entity, int $version): void
    {
        $logEntryRepository->createQueryBuilder('l')
            ->delete()
            ->where('l.objectId = :id')
            ->andWhere('l.objectClass = :className')
            ->andWhere('l.version > :version')
            ->setParameters(
                [
                    'id' => $entity->getId(),
                    'className' => $entityManager->getClassMetadata(get_class($entity))->name,
                    'version' => $version
                ]
            )
            ->getQuery()
            ->execute()
        ;
    }
}
