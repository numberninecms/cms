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
     */
    private ?array $customFields = null;

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
     * @return mixed|null
     */
    public function getCustomField(string $fieldName)
    {
        return $this->customFields ? ($this->customFields[$fieldName] ?? null) : null;
    }

    public function getCustomFieldsStartingWith(string $startsWith): array
    {
        if (!$this->customFields) {
            return [];
        }

        $fields = [];

        foreach ($this->customFields as $field => $value) {
            if (str_starts_with($field, $startsWith)) {
                $fields[$field] = $value;
            }
        }

        return $fields;
    }

    /**
     * @param mixed|null $value
     */
    public function addCustomField(string $fieldName, $value): self
    {
        if (!\is_array($this->customFields)) {
            $this->customFields = [];
        }

        $this->customFields[$fieldName] = $value;

        return $this;
    }

    public function removeCustomField(string $fieldName): self
    {
        if (\is_array($this->customFields) && \array_key_exists($fieldName, $this->customFields)) {
            unset($this->customFields[$fieldName]);
        }

        return $this;
    }
}
