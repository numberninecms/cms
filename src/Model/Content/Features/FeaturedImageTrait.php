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
use NumberNine\Entity\MediaFile;
use Symfony\Component\Serializer\Annotation\Groups;

trait FeaturedImageTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="NumberNine\Entity\MediaFile")
     * @Groups({"featured_image_get", "content_entity_get_full"})
     */
    private ?MediaFile $featuredImage;

    /**
     * @return MediaFile|null
     */
    public function getFeaturedImage(): ?MediaFile
    {
        return $this->featuredImage;
    }

    /**
     * @param MediaFile|null $featuredImage
     * @return self
     */
    public function setFeaturedImage(?MediaFile $featuredImage): self
    {
        $this->featuredImage = $featuredImage;
        return $this;
    }
}
