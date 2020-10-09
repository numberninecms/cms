<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Frontend\Registration;

use NumberNine\Entity\User;
use NumberNine\Form\User\RegistrationFormType;
use NumberNine\Security\UserFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/register", name="numbernine_register")
 */
final class RegisterAction extends AbstractController
{
    public function __invoke(Request $request, UserFactory $userFactory): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userFactory->createUser($user->getUsername(), $user->getEmail(), $form->get('plainPassword')->getData());

            return $this->redirectToRoute('numbernine_homepage');
        }

        return $this->render(
            '@NumberNine/user/registration/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }
}
