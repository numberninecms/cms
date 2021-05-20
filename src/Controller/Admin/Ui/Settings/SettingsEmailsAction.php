<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\Settings;

use NumberNine\Configuration\ConfigurationReadWriter;
use NumberNine\Form\Admin\Settings\AdminSettingsEmailsFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\General\Settings;
use NumberNine\Model\General\SettingsDefaultValues;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/emails/", name="numbernine_admin_settings_emails", methods={"GET", "POST"})
 */
final class SettingsEmailsAction extends AbstractController implements AdminController
{
    public function __invoke(
        ConfigurationReadWriter $configurationReadWriter,
        Request $request
    ): Response {
        $settings = $configurationReadWriter->readMany([
            Settings::MAILER_SENDER_NAME => SettingsDefaultValues::MAILER_SENDER_NAME,
            Settings::MAILER_SENDER_ADDRESS => SettingsDefaultValues::MAILER_SENDER_ADDRESS,
        ]);

        $form = $this->createForm(AdminSettingsEmailsFormType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurationReadWriter->writeMany($form->getData());

            $this->addFlash('success', 'Email settings successfully saved.');
            return $this->redirectToRoute('numbernine_admin_settings_emails', [], Response::HTTP_SEE_OTHER);
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/settings/emails.html.twig', [
            'form' => $form->createView(),
        ], $response);
    }
}
