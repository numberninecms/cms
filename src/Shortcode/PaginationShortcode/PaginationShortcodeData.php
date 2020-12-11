<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\PaginationShortcode;

use NumberNine\Content\ShortcodeData;
use NumberNine\Pagination\Paginator;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PaginationShortcodeData extends ShortcodeData
{
    protected ?Paginator $paginator;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'paginator' => null,
        ]);
    }

    public function toArray(): array
    {
        return [
            'paginator' => $this->paginator,
        ];
    }

    public function setPaginator(?Paginator $paginator): void
    {
        $this->paginator = $paginator;
    }
}
