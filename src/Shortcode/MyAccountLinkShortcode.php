<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode;

use NumberNine\Annotation\Shortcode;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Shortcode(name="my_account_link", label="My Account Link", icon="person")
 */
final class MyAccountLinkShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('loggedOutText')
            ->add('loggedInText')
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'loggedOutText' => 'Login / Register',
            'loggedInText' => 'My account',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'loggedOutText' => $parameters['loggedOutText'],
            'loggedInText' => $parameters['loggedInText'],
        ];
    }
}
