<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Theme;

use NumberNine\Event\ThemeEvent;
use NumberNine\Model\Admin\AdminController;
use NumberNine\Model\Theme\ThemeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appearance/themes/activate/{name}", name="admin_appearance_themes_activate")
 */
final class ActivateAction extends AbstractController implements AdminController
{
    public function __invoke(EventDispatcherInterface $eventDispatcher, string $name): Response
    {
//        $theme = $themeService->activate($name);
//
//        if ($theme instanceof ThemeInterface) {
//            $response = $this->redirectToRoute('admin_update_rebuild', ['config' => $theme->getWebpackConfigName()]);
//
//            /** @var ThemeEvent $event */
//            $event = $eventDispatcher->dispatch(new ThemeEvent($response, $theme));
//
//            return $event->getResponse();
//        }

        throw new BadRequestHttpException();
    }
}
