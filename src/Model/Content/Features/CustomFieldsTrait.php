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

trait CustomFieldsTrait
{
    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"custom_fields_get", "content_entity_get_full"})
     * @var array|null
     */
    private ?array $customFields;

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    public function setCustomFields(?array $customFields): self
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * @param string $fieldName
     * @return mixed|null
     */
    public function getCustomField(string $fieldName)
    {
        return $this->customFields ? ($this->customFields[$fieldName] ?? null) : null;
    }
}
