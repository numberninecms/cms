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

namespace NumberNine\Tests\Functional\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use NumberNine\Entity\Comment;
use NumberNine\Model\Content\CommentStatusInterface;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Pagination\Paginator;
use NumberNine\Repository\CommentRepository;
use NumberNine\Test\UserAwareTestCase;
use NumberNine\Tests\CreateEntitiesHelperTrait;

/**
 * @covers \NumberNine\Repository\CommentRepository
 *
 * @internal
 */
final class CommentRepositoryTest extends UserAwareTestCase
{
    use CreateEntitiesHelperTrait;

    private CommentRepository $commentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commentRepository = static::getContainer()->get(CommentRepository::class);
        $this->entityManager->getConnection()->createQueryBuilder()
            ->delete($this->entityManager->getClassMetadata(Comment::class)->getTableName(), 'c')
            ->execute()
        ;
    }

    public function testFindByContentEntityId(): void
    {
        $post = $this->createPostComments(
            $this->createUser('Contributor'),
            CommentStatusInterface::COMMENT_STATUS_APPROVED,
        );

        $comments = $this->commentRepository->findByContentEntityId($post->getId());

        static::assertEquals($post->getComments()->toArray(), $comments);
    }

    public function testRemoveCommentAuthor(): void
    {
        $post = $this->createPostComments(
            $this->createUser('Contributor'),
            CommentStatusInterface::COMMENT_STATUS_APPROVED,
            1,
        );

        /** @var Comment $comment */
        $comment = $post->getComments()->get(0);
        static::assertEquals($post->getAuthor(), $comment->getAuthor());

        $this->commentRepository->removeCommentAuthor($comment);

        static::assertNull($comment->getAuthor());
        static::assertEquals('Deleted user', $comment->getGuestAuthorName());
    }

    public function testGetPaginatedCollectionQueryBuilder(): void
    {
        $comments = $this->createOrphanComments(5);
        $paginationParameters = (new PaginationParameters())
            ->setFetchCount(2)
        ;

        $queryBuilder = $this->commentRepository->getPaginatedCollectionQueryBuilder($paginationParameters);
        $paginator = new Paginator(new DoctrinePaginator($queryBuilder));

        static::assertEquals(3, $paginator->getLastPage());
        static::assertEquals(\array_slice($comments, 0, 2), $paginator->getQuery()->execute());
    }

    public function testApproveCollection(): void
    {
        $comments = $this->createOrphanComments(5, CommentStatusInterface::COMMENT_STATUS_PENDING);
        $ids = array_map(static fn (Comment $comment) => $comment->getId(), $comments);
        $this->commentRepository->approveCollection($ids);
        $status = $this->fetchStatus($ids);

        static::assertEquals([CommentStatusInterface::COMMENT_STATUS_APPROVED], $status);
    }

    public function testUnapproveCollection(): void
    {
        $comments = $this->createOrphanComments(5, CommentStatusInterface::COMMENT_STATUS_APPROVED);
        $ids = array_map(static fn (Comment $comment) => $comment->getId(), $comments);
        $this->commentRepository->unapproveCollection($ids);
        $status = $this->fetchStatus($ids);

        static::assertEquals([CommentStatusInterface::COMMENT_STATUS_PENDING], $status);
    }

    public function testMarkCollectionAsSpam(): void
    {
        $comments = $this->createOrphanComments(5, CommentStatusInterface::COMMENT_STATUS_PENDING);
        $ids = array_map(static fn (Comment $comment) => $comment->getId(), $comments);
        $this->commentRepository->markCollectionAsSpam($ids);
        $status = $this->fetchStatus($ids);

        static::assertEquals([CommentStatusInterface::COMMENT_STATUS_SPAM], $status);
    }

    public function testDeleteCollection(): void
    {
        $comments = $this->createOrphanComments(1, CommentStatusInterface::COMMENT_STATUS_PENDING);
        $ids = array_map(static fn (Comment $comment) => $comment->getId(), $comments);
        $this->commentRepository->removeCollection($ids);

        static::assertNotNull($comments[0]->getDeletedAt());
    }

    public function testHardDeleteCollection(): void
    {
        $comments = $this->createOrphanComments(1, CommentStatusInterface::COMMENT_STATUS_PENDING);
        $ids = array_map(static fn (Comment $comment) => $comment->getId(), $comments);

        $this->commentRepository->removeCollection($ids);
        static::assertNotNull($comments[0]->getDeletedAt());

        $this->commentRepository->removeCollection($ids);
        $this->expectErrorMessage(
            'Typed property NumberNine\Entity\Comment::$id must not be accessed before initialization',
        );
        $comments[0]->getId();
    }

    public function testRestoreCollection(): void
    {
        $comments = $this->createOrphanComments(1, CommentStatusInterface::COMMENT_STATUS_PENDING);
        $ids = array_map(static fn (Comment $comment) => $comment->getId(), $comments);

        $this->commentRepository->removeCollection($ids);
        static::assertNotNull($comments[0]->getDeletedAt());

        $this->commentRepository->restoreCollection($ids);
        static::assertNull($comments[0]->getDeletedAt());
    }

    /**
     * Testing database instead of ORM is mandatory, as bulk status modifications are made
     * using UPDATE statement, which seems not supported by DAMADoctrineTestBundle.
     */
    private function fetchStatus(array $ids): array
    {
        $status = $this->entityManager->getConnection()->createQueryBuilder()
            ->select('c.status')
            ->from($this->entityManager->getClassMetadata(Comment::class)->getTableName(), 'c')
            ->where('c.id IN (:ids)')
            ->andWhere('c.deleted_at IS NULL')
            ->groupBy('c.status')
            ->setParameter('ids', implode(',', $ids))
            ->execute()
            ->fetchAllAssociative()
        ;

        return array_column($status, 'status');
    }
}
