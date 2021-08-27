<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Content\Features;

use Doctrine\ORM\Mapping as ORM;
use NumberNine\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

trait AuthorTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\User")
     * @Groups({"author_get", "content_entity_get_full"})
     */
    protected ?User $author = null;

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
