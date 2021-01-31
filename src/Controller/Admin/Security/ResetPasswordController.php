<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Security;

use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Entity\User;
use NumberNine\Form\User\ChangePasswordFormType;
use NumberNine\Form\User\ResetPasswordRequestFormType;
use NumberNine\Model\General\Settings;
use NumberNine\Theme\TemplateResolverInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/reset-password")
 */
final class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private ResetPasswordHelperInterface $resetPasswordHelper;
    private TemplateResolverInterface $templateResolver;
    private ConfigurationReadWriter $configurationReadWriter;

    public function __construct(
        ResetPasswordHelperInterface $resetPasswordHelper,
        TemplateResolverInterface $templateResolver,
        ConfigurationReadWriter $configurationReadWriter
    ) {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->templateResolver = $templateResolver;
        $this->configurationReadWriter = $configurationReadWriter;
    }

    /**
     * Display & process form to request a password reset.
     *
     * @Route("", name="numbernine_forgot_password_request")
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }

        return $this->render(
            $this->templateResolver->resolvePath('user/reset_password/request.html.twig')->getTemplateName(),
            [
                'requestForm' => $form->createView(),
            ]
        );
    }

    /**
     * Confirmation page after a user has requested a password reset.
     *
     * @Route("/check-email", name="numbernine_check_email")
     */
    public function checkEmail(): Response
    {
        // We prevent users from directly accessing this page
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            return $this->redirectToRoute('numbernine_forgot_password_request');
        }

        return $this->render(
            $this->templateResolver->resolvePath('user/reset_password/check_email.html.twig')->getTemplateName(),
            [
                'resetToken' => $resetToken,
            ]
        );
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("/reset/{token}", name="numbernine_reset_password")
     */
    public function reset(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        string $token = null
    ): Response {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('numbernine_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            /** @var User $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('numbernine_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('numbernine_login');
        }

        return $this->render(
            $this->templateResolver->resolvePath('user/reset_password/reset.html.twig')->getTemplateName(),
            [
                'resetForm' => $form->createView(),
            ]
        );
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('numbernine_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
            // $this->addFlash('reset_password_error', sprintf(
            //     'There was a problem handling your password reset request - %s',
            //     $e->getReason()
            // ));

            return $this->redirectToRoute('numbernine_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address(
                $this->configurationReadWriter->read(Settings::MAILER_SENDER_ADDRESS, 'no-reply@numberninecms.com'),
                $this->configurationReadWriter->read(Settings::MAILER_SENDER_NAME, 'NumberNine CMS')
            ))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate(
                $this->templateResolver->resolvePath('user/reset_password/email.html.twig')->getTemplateName()
            )
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('numbernine_check_email');
    }
}
