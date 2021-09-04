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

use NumberNine\Model\Theme\ThemeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ThemeEvent extends Event
{
    public function __construct(private Response $response, private ThemeInterface $theme)
    {
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getTheme(): ThemeInterface
    {
        return $this->theme;
    }
}
