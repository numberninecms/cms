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
use Exception;
use NumberNine\Entity\ThemeOptions;

/**
 * @method ThemeOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThemeOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThemeOptions[]    findAll()
 * @method ThemeOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ThemeOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThemeOptions::class);
    }

    /**
     * @param string $themeName
     * @return ThemeOptions Returns a ThemeOptions object corresponding to the given theme name
     */
    public function getOrCreateByThemeName(string $themeName): ThemeOptions
    {
        if (!$themeOptions = $this->findOneBy(['theme' => $themeName])) {
            $themeOptions = (new ThemeOptions())->setTheme($themeName);

            try {
                $this->_em->persist($themeOptions);
                $this->_em->flush();
            } catch (Exception $e) {
            }
        }

        return $themeOptions;
    }
}
