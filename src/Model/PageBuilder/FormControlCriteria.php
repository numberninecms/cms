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

namespace NumberNine\Model\PageBuilder;

final class FormControlCriteria
{
    private string $valueFrom;
    private string $findBy;

    public function __construct(string $valueFrom, string $findBy)
    {
        $this->valueFrom = $valueFrom;
        $this->findBy = $findBy;
    }

    public function getValueFrom(): string
    {
        return $this->valueFrom;
    }

    public function getFindBy(): string
    {
        return $this->findBy;
    }
}
