<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 * @Attributes({
 *     @Attribute("name", type="string"),
 *     @Attribute("editable", type="bool"),
 *     @Attribute("container", type="bool"),
 *     @Attribute("label", type="string"),
 *     @Attribute("icon", type="string"),
 *     @Attribute("description", type="string"),
 *     @Attribute("siblingsPosition", type="array"),
 *     @Attribute("siblingsShortcodes", type="array"),
 * })
 */
final class Shortcode implements DescriptorAnnotation
{
    /**
     * @Required
     */
    public string $name;
    public string $label;
    public bool $editable = false;
    public bool $container = false;
    public string $icon = 'dashboard';
    public string $description;

    /**
     * Can contain any of these values: top, bottom, left, right
     * @var string[]
     */
    public array $siblingsPosition = ['top', 'bottom'];

    /**
     * Values can be any other shortcodes to restrict its content.
     * If no value is specified, any shortcode can be added.
     * @var string[]
     */
    public array $siblingsShortcodes = [];
}
