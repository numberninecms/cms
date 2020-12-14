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
 * @Shortcode(name="link", label="Link", container=true, icon="link")
 */
final class LinkShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('href', null, ['label' => 'Url'])
            ->add('title', null, ['label' => 'Title tooltip text'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'href' => '',
            'title' => '',
            'content' => '',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'href' => $parameters['href'],
            'title' => $parameters['title'],
            'content' => $parameters['content'],
        ];
    }
}
