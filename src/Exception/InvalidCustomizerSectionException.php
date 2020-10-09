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
use NumberNine\Model\Customizer\Section\SectionInterface;

/**
 * Class InvalidCustomizerSectionException
 * @package NumberNine\Exception
 */
final class InvalidCustomizerSectionException extends Exception
{
    /**
     * InvalidCustomizerSectionException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(sprintf('"%s" must implement %s.', $name, SectionInterface::class));
    }
}
