<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Event\Theme;

use NumberNine\Model\Theme\ThemeInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ThemeInitializeEvent extends Event
{
    /** @var ThemeInterface */
    private $theme;

    /**
     * ThemeInitializeEvent constructor.
     * @param ThemeInterface $theme
     */
    public function __construct(ThemeInterface $theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return ThemeInterface
     */
    public function getTheme(): ThemeInterface
    {
        return $this->theme;
    }

    /**
     * @param ThemeInterface $theme
     * @return ThemeInitializeEvent
     */
    public function setTheme(ThemeInterface $theme): self
    {
        $this->theme = $theme;
        return $this;
    }
}
