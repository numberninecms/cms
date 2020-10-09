<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Primitive;


final class Range
{
    private float $min;
    private float $max;

    public function __construct(float $min = 0.0, float $max = 0.0)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function setMin(float $min): self
    {
        $this->min = $min;
        return $this;
    }

    public function getMax(): float
    {
        return $this->max;
    }

    public function setMax(float $max): self
    {
        $this->max = $max;
        return $this;
    }
}
