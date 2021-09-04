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
use NumberNine\Exception\InvalidTimestampException;
use RelativeTime\RelativeTime;
use Twig\Extension\RuntimeExtensionInterface;

final class DateTimeRuntime implements RuntimeExtensionInterface
{
    public function __construct(private RelativeTime $timeService)
    {
    }

    /**
     * @param int|DateTime $date Unix timestamp or DateTime object
     */
    public function getTimeLeftInWords(int|\DateTime $date): string
    {
        $date = $this->getTimestamp($date);

        return $this->timeService->timeLeft(date('Y-m-d H:i:s', $date));
    }

    /**
     * @param int|DateTime $date Unix timestamp or DateTime object
     */
    public function getTimeAgoInWords(int|\DateTime $date): string
    {
        $date = $this->getTimestamp($date);

        return $this->timeService->timeAgo(date('Y-m-d H:i:s', $date));
    }

    /**
     * @param int|DateTime $date Unix timestamp or DateTime object
     */
    private function getTimestamp(int|\DateTime $date): int
    {
        if ($date instanceof DateTime) {
            $date = $date->getTimestamp();
        }

        try {
            new DateTime('@' . $date);
        } catch (\Exception) {
            throw new InvalidTimestampException((string)$date);
        }

        return (int)$date;
    }
}
