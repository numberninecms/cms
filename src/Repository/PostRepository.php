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

use Doctrine\Persistence\ManagerRegistry;
use NumberNine\Entity\Post;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PostRepository extends AbstractContentEntityRepository
{
    use FindFeaturedImageTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[]
     */
    public function getRecentPosts(int $count): array
    {
        return $this->createQueryBuilder('p')
            ->where("p.status = 'publish'")
            ->andWhere("p.customType = 'post'")
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findSiblings(Post $post): array
    {
        $ids = array_column(
            $this->createQueryBuilder('p')
                ->select('p.id')
                ->where("p.status = 'publish'")
                ->andWhere('p.customType = :customType')
                ->orderBy('p.publishedAt', 'ASC')
                ->setParameter('customType', $post->getCustomType())
                ->getQuery()
                ->getResult(),
            'id'
        );

        $currentPosition = (int) array_search($post->getId(), $ids, true);
        $previousId = $currentPosition >= 1 ? $ids[$currentPosition - 1] : false;
        $nextId = ($currentPosition < \count($ids) - 1) ? $ids[$currentPosition + 1] : false;

        if ($previousId === false && $nextId === false) {
            return ['previous' => null, 'next' => null];
        }

        $siblings = $this->createQueryBuilder('p')
            ->where("p.status = 'publish'")
            ->andWhere('p.id IN (:ids)')
            ->orderBy('p.publishedAt', 'ASC')
            ->setParameter('ids', array_filter([$previousId, $nextId]))
            ->getQuery()
            ->getResult()
        ;

        return [
            'previous' => $previousId ? $siblings[0] : null,
            'next' => $nextId ? $siblings[\count($siblings) - 1] : null,
        ];
    }
}
