<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Component\Account\LoginForm;

use NumberNine\Event\LoginPathsEvent;
use NumberNine\Form\User\LoginFormType;
use NumberNine\Model\Component\ComponentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginForm implements ComponentInterface, EventSubscriberInterface
{
    private AuthenticationUtils $authenticationUtils;
    private FormFactoryInterface $formFactory;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private ?Request $request;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory = $formFactory;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginPathsEvent::class => 'addLoginPath',
        ];
    }

    public function addLoginPath(LoginPathsEvent $event): void
    {
        if ($this->request) {
            $event->addPath($this->request->getRequestUri());
        }
    }

    public function getTemplateParameters(): array
    {
        return [
            'form' => $this->getForm(),
            'error' => $this->getError(),
        ];
    }

    private function getError(): ?AuthenticationException
    {
        return $this->authenticationUtils->getLastAuthenticationError();
    }

    private function getForm(): FormView
    {
        return $this->getFormObject()->createView();
    }

    private function getFormObject(): FormInterface
    {
        return $this->formFactory->create(
            LoginFormType::class,
            [
                'username' => $this->authenticationUtils->getLastUsername(),
                '_csrf_token' => $this->csrfTokenManager->getToken('authenticate'),
            ]
        );
    }
}
