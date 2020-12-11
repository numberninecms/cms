<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\BlockShortcode;

use NumberNine\Annotation\Form\Control;
use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BlockShortcodeData extends ShortcodeData
{
    /**
     * @Control\ContentEntity(label="Block", contentType="block")
     */
    protected string $id;

    protected string $blockContent;

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'id' => ''
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'blockContent' => $this->blockContent,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setBlockContent(string $blockContent): void
    {
        $this->blockContent = $blockContent;
    }
}
