<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Admin;

use NumberNine\Model\Admin\AdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="numbernine_admin_index", methods={"GET"})
 */
final class AdminIndexAction implements AdminController
{
    public function __invoke(string $publicPath): Response
    {
        return (new Response())
            ->setContent((string)file_get_contents($publicPath . '/admin/index.html'));
    }
}
