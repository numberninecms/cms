<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Entity;

use Doctrine\ORM\Mapping as ORM;
use NumberNine\Attribute\NormalizationContext;

/**
 * @ORM\Entity(repositoryClass="NumberNine\Repository\PostRepository")
 */
#[NormalizationContext(groups: ['content_entity_get', 'web_access_get', 'author_get'])]
class Post extends ContentEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setStatus(self::STATUS_DRAFT);
    }
}
