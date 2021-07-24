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

namespace NumberNine\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ShortcodeTestCase extends KernelTestCase
{
    protected const SHORTCODE = '';
    protected $shortcode;

    public function setUp(): void
    {
        parent::setUp();
        $this->shortcode = static::getContainer()->get(static::SHORTCODE);
    }

    protected function processParameters(array $parameters): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined(array_keys($parameters));
        $this->shortcode->configureParameters($resolver);
        $parameters = $resolver->resolve($parameters);
        return $this->shortcode->processParameters($parameters);
    }
}
