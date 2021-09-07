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
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use NumberNine\Entity\ContentEntity;
use NumberNine\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'content_entities/{type}/{id<\d+>}/revisions/', name: 'numbernine_admin_contententity_revisions_get_collection', options: ['expose' => true], methods: [
    'GET',
], priority: 100, )]
final class ContentEntityRevisionsGetAction
{
    public function __invoke(
        Request $request,
        ResponseFactory $responseFactory,
        EntityManagerInterface $entityManager,
        ContentEntity $entity
    ): JsonResponse {
        /** @var LogEntryRepository $logEntryRepository */
        $logEntryRepository = $entityManager->getRepository(LogEntry::class);

        $entries = $logEntryRepository->getLogEntries($entity);
        $revisions = array_map(
            fn (LogEntry $entry) => array_merge(
                $entry->getData(),
                ['version' => $entry->getVersion(), 'date' => $entry->getLoggedAt()]
            ),
            $entries
        );

        return $responseFactory->createSerializedJsonResponse($revisions);
    }
}
