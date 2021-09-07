<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Shortcode;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ShortcodeInterface
{
    public function configureParameters(OptionsResolver $resolver): void;

    public function processParameters(array $parameters): array;
}
