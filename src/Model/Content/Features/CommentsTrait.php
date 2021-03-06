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

use ArrayAccess;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use NumberNine\Entity\Comment;
use Traversable;

trait CommentsTrait
{
    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $commentStatus;

    /**
     * @ORM\OneToMany(targetEntity="NumberNine\Entity\Comment", mappedBy="contentEntity")
     * @var Collection|Comment[]
     */
    private Collection $comments;

    public function getCommentStatus(): ?string
    {
        return $this->commentStatus;
    }

    public function setCommentStatus(string $commentStatus): self
    {
        $this->commentStatus = $commentStatus;

        return $this;
    }

    public function getComments(): Traversable
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function addComment(Comment $comment): self
    {
        $this->comments->add($comment);

        return $this;
    }
}
