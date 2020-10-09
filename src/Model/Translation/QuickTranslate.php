<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Translation;

trait QuickTranslate
{
    public function __(?string $text, array $parameters = [], string $locale = null): string
    {
        return $this->translator->trans((string)$text, $parameters, 'numbernine', $locale);
    }
}
