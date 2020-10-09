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
use NumberNine\Model\Content\PublishingStatusInterface;
use Symfony\Component\Serializer\Annotation\Groups;

trait WebAccessTrait
{
    /**
     * @ORM\Column(type="text")
     * @Groups({"web_access_get", "menu_get", "content_entity_get_full"})
     * @Gedmo\Versioned
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"web_access_get", "content_entity_get_full"})
     */
    private ?string $status = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password = null;

    /**
     * @var string|null
     * @Groups({"web_access_get", "content_entity_get_full"})
     */
    private ?string $publicUrl = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPublicUrl(): ?string
    {
        return $this->publicUrl;
    }

    public function setPublicUrl(?string $publicUrl): self
    {
        $this->publicUrl = $publicUrl;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->status === PublishingStatusInterface::STATUS_PUBLISH;
    }

    public function isPrivate(): bool
    {
        return $this->status === PublishingStatusInterface::STATUS_PRIVATE;
    }

    public function isDraft(): bool
    {
        return $this->status === PublishingStatusInterface::STATUS_DRAFT;
    }
}
