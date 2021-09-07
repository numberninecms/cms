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
use NumberNine\Content\ContentEntityRenderer;
use NumberNine\Model\PageBuilder\Control\ContentEntityControl;
use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;
use NumberNine\Model\Shortcode\AbstractShortcode;
use NumberNine\Model\Shortcode\EditableShortcodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[Shortcode(name: 'block', label: 'Block', icon: 'puzzle-piece')]
final class BlockShortcode extends AbstractShortcode implements EditableShortcodeInterface
{
    public function __construct(private ContentEntityRenderer $contentEntityRenderer)
    {
    }

    public function buildPageBuilderForm(PageBuilderFormBuilderInterface $builder): void
    {
        $builder
            ->add('id', ContentEntityControl::class, ['contentType' => 'block'])
        ;
    }

    public function configureParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'id' => '',
        ]);
    }

    public function processParameters(array $parameters): array
    {
        return [
            'content' => $this->getBlockContent($parameters),
        ];
    }

    public function getBlockContent(array $parameters): string
    {
        if (!$parameters['id']) {
            return '[block]';
        }

        return $this->contentEntityRenderer->renderBySlug($parameters['id'], false);
    }
}
