<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\ButtonShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(name="button", label="Button", editable=true, icon="crop_7_5")
 */
final class ButtonShortcode extends AbstractShortcode
{
    public function process($data): void
    {
        if ($data->getContent()) {
            $data->setText($data->getContent());
        }
    }
}
