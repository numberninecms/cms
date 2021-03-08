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

namespace NumberNine\Model\Content;

use NumberNine\Model\PageBuilder\PageBuilderFormBuilderInterface;

interface EditorExtensionBuilderInterface
{
    public const COMPONENT_TYPE_TAB = 'tab';
    public const COMPONENT_TYPE_SIDEBAR = 'sidebar';

    public function add(string $child, ?string $type = null, array $options = []): self;
    public function getBuilder(string $name): PageBuilderFormBuilderInterface;
}
