<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event;

use NumberNine\Entity\Term;
use Symfony\Contracts\EventDispatcher\Event;

final class CurrentRequestTermEvent extends Event
{
    public function __construct(private Term $term)
    {
    }

    public function getTerm(): Term
    {
        return $this->term;
    }

    public function setTerm(Term $term): void
    {
        $this->term = $term;
    }
}
