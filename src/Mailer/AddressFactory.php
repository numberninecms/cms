<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Mailer;

use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Model\General\Settings;
use NumberNine\Model\General\SettingsDefaultValues;
use Symfony\Component\Mime\Address;

final class AddressFactory
{
    public function __construct(private ConfigurationReadWriter $configurationReadWriter)
    {
    }

    public function createApplicationAddress(): Address
    {
        return new Address(
            $this->configurationReadWriter->read(
                Settings::MAILER_SENDER_ADDRESS,
                SettingsDefaultValues::MAILER_SENDER_ADDRESS
            ),
            $this->configurationReadWriter->read(
                Settings::MAILER_SENDER_NAME,
                SettingsDefaultValues::MAILER_SENDER_NAME
            )
        );
    }
}
