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
use NumberNine\Form\Admin\Settings\AdminSettingsGeneralFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\General\Settings;
use NumberNine\Model\General\SettingsDefaultValues;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/general/", name="numbernine_admin_settings_general", methods={"GET", "POST"})
 */
final class SettingsGeneralAction extends AbstractController implements AdminController
{
    public function __invoke(
        ConfigurationReadWriter $configurationReadWriter,
        Request $request
    ): Response {
        $settings = $configurationReadWriter->readMany(
            [
                Settings::SITE_TITLE => SettingsDefaultValues::SITE_TITLE,
                Settings::SITE_DESCRIPTION => SettingsDefaultValues::SITE_DESCRIPTION,
                Settings::PAGE_FOR_FRONT,
                Settings::PAGE_FOR_POSTS,
                Settings::PAGE_FOR_PRIVACY,
                Settings::PAGE_FOR_MY_ACCOUNT,
            ]
        );

        $form = $this->createForm(AdminSettingsGeneralFormType::class, array_merge(
            $settings,
            ['blog_as_homepage' => !(bool)$settings[Settings::PAGE_FOR_FRONT]],
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurationReadWriter->writeMany($form->getData());

            $this->addFlash('success', 'General settings successfully saved.');
            return $this->redirectToRoute('numbernine_admin_settings_general');
        }

        return $this->render('@NumberNine/admin/settings/general.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
