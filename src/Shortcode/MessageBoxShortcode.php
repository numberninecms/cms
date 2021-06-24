<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;

/**
 * @Shortcode(name="message_box", container=true, label="Message Box", icon="mdi-card-text-outline")
 */
final class MessageBoxShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
    }
}
