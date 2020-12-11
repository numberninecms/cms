<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\MyAccountLinkShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MyAccountLinkShortcodeData extends ShortcodeData
{
    /**
     * @Control\TextBox(label="Logged out text")
     */
    protected string $loggedOutText;

    /**
     * @Control\TextBox(label="Logged out text")
     */
    protected string $loggedInText;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'loggedOutText' => 'Login / Register',
            'loggedInText' => 'My account',
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'loggedOutText' => $this->loggedOutText,
            'loggedInText' => $this->loggedInText,
        ];
    }
}
