<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Exception;

use Exception;
use NumberNine\Model\Customizer\Control\ControlInterface;

/**
 * Class InvalidCustomizerControlException
 * @package NumberNine\Exception
 */
final class InvalidCustomizerControlException extends Exception
{
    /**
     * InvalidCustomizerControlException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(sprintf('"%s" must implement %s.', $name, ControlInterface::class));
    }
}
