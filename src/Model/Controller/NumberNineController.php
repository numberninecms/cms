<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Controller;

use NumberNine\Model\Translation\QuickTranslate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class NumberNineController extends AbstractController
{
    use QuickTranslate;

    /**
     * NumberNineController constructor.
     */
    public function __construct(private TranslatorInterface $translator)
    {
    }
}
