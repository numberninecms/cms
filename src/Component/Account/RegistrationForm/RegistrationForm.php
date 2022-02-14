<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Component\Account\RegistrationForm;

use NumberNine\Entity\User;
use NumberNine\Event\RegistrationFormBuilderEvent;
use NumberNine\Event\RegistrationFormSuccessEvent;
use NumberNine\Form\User\RegistrationFormType;
use NumberNine\Model\Component\AbstractFormComponent;
use NumberNine\Security\UserAuthenticator;
use NumberNine\Security\UserFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RegistrationForm extends AbstractFormComponent
{
    protected array $supportedTemplates = ['page/my_account.html.twig'];

    public function __construct(
        private FormFactoryInterface $formFactory,
        private UserFactory $userFactory,
        private UserAuthenticator $userAuthenticator,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    protected function getForm(): FormInterface
    {
        if (!$this->form) {
            $builder = $this->formFactory->createBuilder(RegistrationFormType::class, new User());

            /** @var RegistrationFormBuilderEvent $event */
            $event = $this->eventDispatcher->dispatch(new RegistrationFormBuilderEvent($builder));

            $this->form = $event->getBuilder()->getForm();
        }

        return $this->form;
    }

    protected function handleSubmittedAndValidForm(Request $request): Response
    {
        $form = $this->getForm();

        $user = $this->userFactory->createUser(
            $form->get('username')->getData(),
            $form->get('email')->getData(),
            $form->get('plainPassword')->getData(),
        );

        $response = $this->userAuthenticator->authenticateUser($user);

        /** @var RegistrationFormSuccessEvent $registrationFormSuccessEvent */
        $registrationFormSuccessEvent = $this->eventDispatcher->dispatch(new RegistrationFormSuccessEvent(
            $user,
            $response,
            $form,
        ));

        return $registrationFormSuccessEvent->getResponse() ?? throw new \Exception();
    }
}
