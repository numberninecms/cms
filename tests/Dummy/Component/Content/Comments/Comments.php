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

namespace NumberNine\Tests\Dummy\Component\Content\Comments;

use NumberNine\Exception\NotImplementedException;
use NumberNine\Model\Component\AbstractFormComponent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Comments extends AbstractFormComponent
{
    protected function handleSubmittedAndValidForm(Request $request): Response
    {
        throw new NotImplementedException();
    }

    protected function getForm(): FormInterface
    {
        throw new NotImplementedException();
    }
}
