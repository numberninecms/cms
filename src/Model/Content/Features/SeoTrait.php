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
use Symfony\Component\Serializer\Annotation\Groups;

trait SeoTrait
{
    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $seo = null;

    public function getSeo(): ?array
    {
        return $this->seo;
    }

    public function setSeo(?array $seo): self
    {
        $this->seo = $seo;
        return $this;
    }

    /**
     * @Groups({"seo_get", "content_entity_get_full"})
     */
    public function getSeoTitle(): ?string
    {
        return is_array($this->seo) && array_key_exists('title', $this->seo) ? $this->seo['title'] : null;
    }

    public function setSeoTitle(?string $title): self
    {
        if (!is_array($this->seo)) {
            $this->seo = [];
        }

        $this->seo['title'] = $title;
        return $this;
    }

    /**
     * @Groups({"seo_get", "content_entity_get_full"})
     */
    public function getSeoDescription(): ?string
    {
        return is_array($this->seo) && array_key_exists('title', $this->seo) ? $this->seo['description'] : null;
    }

    public function setSeoDescription(?string $description): self
    {
        if (!is_array($this->seo)) {
            $this->seo = [];
        }

        $this->seo['description'] = $description;
        return $this;
    }
}
