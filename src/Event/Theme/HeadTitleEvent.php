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

final class HeadTitleEvent extends GenericEvent
{
    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->setObject($title);
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return '<title>' . $this->getObject() . '</title>';
    }
}
