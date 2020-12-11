<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Shortcode\MenuShortcode;

use NumberNine\Content\ShortcodeData;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function NumberNine\Common\Util\ArrayUtil\array_depth;

final class MenuShortcodeData extends ShortcodeData
{
    /**
     * @Control\Menu(label="Menu")
     */
    protected ?int $id = null;

    protected array $menuItems = [];

    protected function configureShortcodeParameters(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'id' => null,
        ]);
    }

    public function getTemplateParameters(): array
    {
        return [
            'menuItems' => $this->menuItems,
            'depth' => array_depth($this->menuItems),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMenuItems(): array
    {
        return $this->menuItems;
    }

    public function setMenuItems(array $menuItems): void
    {
        $this->menuItems = $menuItems;
    }
}
