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

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use NumberNine\Entity\MediaFile;

/**
 * @method MediaFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaFile[]    findAll()
 * @method MediaFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class MediaFileRepository extends AbstractContentEntityRepository
{
    use FindByCustomFieldTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaFile::class);
    }

    /**
     * @return Query
     */
    public function findImagesQuery(): Query
    {
        return $this->createQueryBuilder('m')
            ->where('m.mimeType IN (:mimeTypes)')
            ->setParameter('mimeTypes', ['image/jpeg', 'image/png', 'image/gif'])
            ->getQuery();
    }
}
