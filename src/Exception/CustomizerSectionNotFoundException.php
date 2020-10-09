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

/**
 * Class CustomizerSectionNotFoundException
 * @package NumberNine\Exception
 */
final class CustomizerSectionNotFoundException extends Exception
{
    /**
     * CustomizerSectionNotFoundException constructor.
     * @param string $sectionId
     */
    public function __construct(string $sectionId)
    {
        parent::__construct(sprintf('Section with ID "%s" was not found. Register it prior to using it.', $sectionId));
    }
}
