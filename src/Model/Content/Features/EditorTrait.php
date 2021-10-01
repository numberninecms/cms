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
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

trait EditorTrait
{
    /**
     * @Gedmo\Versioned
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['editor_get', 'content_entity_get_full'])]
    private ?string $content = null;

    /**
     * @Gedmo\Versioned
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['editor_get', 'content_entity_get_full'])]
    private ?string $excerpt = null;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }
}
