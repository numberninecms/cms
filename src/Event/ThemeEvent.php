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

/**
 * Class ThemeEvent
 * @package NumberNine\Event
 */
final class ThemeEvent extends Event
{
    /** @var Response */
    private $response;

    /** @var ThemeInterface */
    private $theme;

    /**
     * ThemeEvent constructor.
     * @param Response $response
     * @param ThemeInterface $theme
     */
    public function __construct(Response $response, ThemeInterface $theme)
    {
        $this->response = $response;
        $this->theme = $theme;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return ThemeInterface
     */
    public function getTheme(): ThemeInterface
    {
        return $this->theme;
    }
}
