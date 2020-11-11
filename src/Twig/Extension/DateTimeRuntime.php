<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Twig\Extension;

use DateTime;
use RelativeTime\RelativeTime;
use Twig\Extension\RuntimeExtensionInterface;

final class DateTimeRuntime implements RuntimeExtensionInterface
{
    private RelativeTime $timeService;

    public function __construct(RelativeTime $timeService)
    {
        $this->timeService = $timeService;
    }

    /**
     * @param mixed $date Unix timestamp or DateTime object
     * @return string
     */
    public function getTimeLeftInWords($date): string
    {
        if ($date instanceof DateTime) {
            $date = $date->getTimestamp();
        }

        return $this->timeService->timeLeft(date('Y-m-d H:i:s', $date));
    }

    /**
     * @param mixed $date Unix timestamp or DateTime object
     * @return string
     */
    public function getTimeAgoInWords($date): string
    {
        if ($date instanceof DateTime) {
            $date = $date->getTimestamp();
        }

        return $this->timeService->timeAgo(date('Y-m-d H:i:s', $date));
    }
}
