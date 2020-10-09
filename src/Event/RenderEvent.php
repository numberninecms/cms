<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event;

use NumberNine\Model\Component\RenderableInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class RenderEvent extends Event
{
    /** @var RenderableInterface */
    private $component;

    /** @var array */
    private $values;

    /**
     * @param RenderableInterface $component
     * @param array $values
     */
    public function __construct(RenderableInterface $component, array $values)
    {
        $this->component = $component;
        $this->values = $values;
    }

    /**
     * @return RenderableInterface
     */
    public function getComponent(): RenderableInterface
    {
        return $this->component;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return RenderEvent
     */
    public function setValues(array $values): self
    {
        $this->values = $values;
        return $this;
    }
}
