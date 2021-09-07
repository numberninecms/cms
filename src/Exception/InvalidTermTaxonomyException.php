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

namespace NumberNine\Exception;

use NumberNine\Entity\Taxonomy;
use NumberNine\Entity\Term;
use RuntimeException;

final class InvalidTermTaxonomyException extends RuntimeException
{
    public function __construct(Taxonomy $taxonomy, Term $term)
    {
        parent::__construct(sprintf(
            'Term ID "%d" does not belong to taxonomy "%s".',
            $term->getId(),
            $taxonomy->getName(),
        ));
    }
}
