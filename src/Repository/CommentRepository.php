<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use NumberNine\Entity\Comment;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CommentRepository extends ServiceEntityRepository
{
    private TranslatorInterface $translator;

    public function __construct(ManagerRegistry $registry, TranslatorInterface $translator)
    {
        parent::__construct($registry, Comment::class);
        $this->translator = $translator;
    }

    public function findByContentEntityId(int $contentEntityId): array
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'a')
            ->leftJoin('c.author', 'a')
            ->where('c.contentEntity = :contentEntityId')
            ->andWhere('c.parent IS NULL')
            ->orderBy('c.createdAt', 'asc')
            ->setParameter('contentEntityId', $contentEntityId)
            ->getQuery()
            ->getResult();
    }

    public function removeCommentAuthor(Comment $comment): void
    {
        $comment
            ->setGuestAuthorName($this->translator->trans('Deleted user', [], 'numbernine'))
            ->setGuestAuthorEmail(null)
            ->setGuestAuthorUrl(null)
            ->setAuthor(null);
    }
}
