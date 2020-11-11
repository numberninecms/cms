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
    private Term $term;

    /**
     * @param Term $term
     */
    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    /**
     * @return Term
     */
    public function getTerm(): Term
    {
        return $this->term;
    }

    /**
     * @param Term $term
     */
    public function setTerm(Term $term): void
    {
        $this->term = $term;
    }
}
