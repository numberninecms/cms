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

use NumberNine\Model\Component\OptionsAndSettingsInjectableInterface;
use NumberNine\Model\Component\RenderableInterface;

interface ShortcodeInterface extends RenderableInterface, OptionsAndSettingsInjectableInterface
{
    public function renderPageBuilderTemplate(): string;

    public function getParameters(bool $isSerialization = false): array;

    public function setParameters(array $parameters, bool $isSerialization = false): ShortcodeInterface;
}
