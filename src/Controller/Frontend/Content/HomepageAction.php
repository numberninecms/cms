<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Frontend\Content;

use NumberNine\EventSubscriber\RouteRegistrationEventSubscriber;
use NumberNine\Model\General\Settings;
use NumberNine\Content\ContentService;
use NumberNine\Configuration\ConfigurationReadWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The route of this action is dynamically generated
 * @see RouteRegistrationEventSubscriber
 */
final class HomepageAction extends AbstractController
{
    public function __invoke(Request $request, ContentService $contentService, ConfigurationReadWriter $configurationReadWriter): Response
    {
        $homepage = $configurationReadWriter->read(Settings::PAGE_FOR_FRONT);

        if ($homepage) {
            return $this->forward(
                ContentEntityShowAction::class,
                array_merge(
                    $request->attributes->get('_route_params'),
                    [
                        '_content_type' => $contentService->getContentType('page'),
                        'id' => $homepage
                    ]
                )
            );
        }

        return $this->forward(
            ContentEntityIndexAction::class,
            array_merge(
                $request->attributes->get('_route_params'),
                [
                    'type' => 'post'
                ]
            )
        );
    }
}
