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

use NumberNine\Form\Admin\Settings\AdminSettingsGeneralFormType;
use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/general/", name="numbernine_admin_settings_general", methods={"GET"})
 */
final class SettingsGeneralAction extends AbstractController implements AdminController
{
    public function __invoke(string $publicPath): Response
    {
        $form = $this->createForm(AdminSettingsGeneralFormType::class);

        return $this->render('@NumberNine/admin/settings/general.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
