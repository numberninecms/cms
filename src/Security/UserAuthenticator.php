<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Security;

use NumberNine\Entity\User;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

final class UserAuthenticator
{
    private GuardAuthenticatorHandler $guardHandler;
    private LoginFormAuthenticator $formAuthenticator;
    private ?Request $request;

    public function __construct(
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $formAuthenticator,
        RequestStack $requestStack
    ) {
        $this->guardHandler = $guardHandler;
        $this->formAuthenticator = $formAuthenticator;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param User $user
     * @return Response|null
     */
    public function authenticateUser(User $user): ?Response
    {
        if (!$this->request) {
            throw new RuntimeException('Unable to authenticate user.');
        }

        return $this->guardHandler->authenticateUserAndHandleSuccess($user, $this->request, $this->formAuthenticator, 'main');
    }
}
