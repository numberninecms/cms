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

namespace NumberNine\Event;

use NumberNine\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

final class RegistrationFormSuccessEvent extends Event
{
    public function __construct(private User $user, private ?Response $response, private ?FormInterface $form = null)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getForm(): ?FormInterface
    {
        return $this->form;
    }
}
