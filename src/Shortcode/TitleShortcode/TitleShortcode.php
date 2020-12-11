<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\TitleShortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\Shortcode\AbstractShortcode;

/**
 * @Shortcode(name="title", editable=true, label="Title", icon="title")
 */
final class TitleShortcode extends AbstractShortcode
{
    /**
     * @param TitleShortcodeData $data
     */
    public function process($data): void
    {
        if (!$data->getText() && trim($data->getContent())) {
            $data->setText(trim($data->getContent()));
        }
    }
}
