<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Component;

use NumberNine\Model\Component\Features\OptionsAndSettingsInjectionTrait;
use NumberNine\Model\Component\Features\RenderTrait;

/**
 * Base component designed to create components faster.
 * Components are designed to be services so dependencies can be injected in constructor.
 */
abstract class AbstractComponent implements ComponentInterface
{
    use RenderTrait;
    use OptionsAndSettingsInjectionTrait;
}
