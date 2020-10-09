<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event\Customizer;

use NumberNine\Model\Customizer\Builder\CustomizerBuilder;
use Symfony\Contracts\EventDispatcher\Event;

final class CustomizerBuilderBeforeInitEvent extends Event
{
    /** @var CustomizerBuilder */
    private $builder;

    /**
     * CustomizerBuilderEvent constructor.
     * @param CustomizerBuilder $builder
     */
    public function __construct(CustomizerBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return CustomizerBuilder
     */
    public function getBuilder(): CustomizerBuilder
    {
        return $this->builder;
    }
}
