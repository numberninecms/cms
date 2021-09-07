<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class HiddenCustomFieldsEvent extends Event
{
    private array $fieldsToHide;

    public function __construct(array $fieldsToHide)
    {
        $this->fieldsToHide = $fieldsToHide;
    }

    public function getFieldsToHide(): array
    {
        return $this->fieldsToHide;
    }

    public function setFieldsToHide(array $fieldsToHide): self
    {
        $this->fieldsToHide = $fieldsToHide;

        return $this;
    }

    public function addFieldToHide(string $fieldName): self
    {
        if (!\in_array($fieldName, $this->fieldsToHide, true)) {
            $this->fieldsToHide[] = $fieldName;
        }

        return $this;
    }

    public function addFieldsToHide(array $fieldsName): self
    {
        $this->fieldsToHide = array_unique($this->fieldsToHide + $fieldsName);

        return $this;
    }
}
