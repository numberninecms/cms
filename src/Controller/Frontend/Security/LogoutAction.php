<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Controller\Frontend\Security;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[\Symfony\Component\Routing\Annotation\Route(path: '/logout', name: 'numbernine_logout')]
final class LogoutAction extends AbstractController
{
    /**
     * @return never
     */
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        throw new Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
