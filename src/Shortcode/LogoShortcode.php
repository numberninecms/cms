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

use NumberNine\Attribute\Shortcode;
use NumberNine\Model\PageBuilder\Control\ImageControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[Shortcode(name: 'logo', label: 'Site logo', description: 'Displays the site logo with dynamic header tag for better SEO.', icon: 'mdi-symfony')]
final class LogoShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('logoImage', ImageControl::class, ['label' => 'Logo image'])
            ->add('fallbackText')
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'logoImage' => '',
            'fallbackText' => '',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'logoImage' => $parameters['logoImage'],
            'fallbackText' => $parameters['fallbackText'],
        ];
    }
}
