<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin\Ui\Dashboard;

use NumberNine\Model\Admin\AdminController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="numbernine_admin_index", methods={"GET"})
 */
final class DashboardIndexAction extends AbstractController implements AdminController
{
    public function __invoke(string $publicPath): Response
    {
        return $this->render('@NumberNine/admin/dashboard/index.html.twig');
    }
}
