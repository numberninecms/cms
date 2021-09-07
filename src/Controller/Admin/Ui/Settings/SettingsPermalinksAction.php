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
use NumberNine\Content\ContentService;
use NumberNine\Form\Admin\Settings\AdminSettingsPermalinksFormType;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\General\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/settings/permalinks/', name: 'numbernine_admin_settings_permalinks', methods: ['GET', 'POST'])]
final class SettingsPermalinksAction extends AbstractController implements AdminController
{
    public function __invoke(
        ConfigurationReadWriter $configurationReadWriter,
        ContentService $contentService,
        Request $request
    ): Response {
        $defaultPermalinks = [];

        foreach ($contentService->getContentTypes() as $contentType) {
            $defaultPermalinks[$contentType->getName()] = $contentType->getPermalink();
        }

        $settings = $configurationReadWriter->readMany([
            Settings::PERMALINKS => $defaultPermalinks,
        ]);

        $form = $this->createForm(AdminSettingsPermalinksFormType::class, $settings['permalinks']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configurationReadWriter->writeMany([
                Settings::PERMALINKS => $form->getData(),
            ]);

            $this->addFlash('success', 'Permalinks successfully saved.');

            return $this->redirectToRoute('numbernine_admin_settings_permalinks', [], Response::HTTP_SEE_OTHER);
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);

        return $this->render('@NumberNine/admin/settings/permalinks.html.twig', [
            'form' => $form->createView(),
        ], $response);
    }
}
