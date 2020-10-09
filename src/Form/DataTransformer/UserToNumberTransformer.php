<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\User;

final class UserToNumberTransformer extends AbstractEntityToNumberTransformer
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }
}
