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
use NumberNine\Event\LoginPathsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private EventDispatcherInterface $eventDispatcher,
        private AuthorizationCheckerInterface $authorizationChecker,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function supports(Request $request): bool
    {
        /** @var LoginPathsEvent $loginPathsEvent */
        $loginPathsEvent = $this->eventDispatcher->dispatch(new LoginPathsEvent());

        return ($request->attributes->get('_route') === 'numbernine_login'
                || \in_array($request->getRequestUri(), $loginPathsEvent->getPaths(), true))
            && $request->isMethod('POST')
            && $request->request->get('username');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            if ($request->attributes->get('_route') === 'numbernine_login') {
                if ($this->authorizationChecker->isGranted(Capabilities::ACCESS_ADMIN)) {
                    $targetPath = $this->urlGenerator->generate('numbernine_admin_index');
                } else {
                    $targetPath = $this->urlGenerator->generate('numbernine_homepage');
                }
            }

            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('numbernine_homepage'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($request->getUri());
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $token = $this->tokenStorage->getToken();

        $url = ($token && $token->getUser() instanceof User)
            ? $this->urlGenerator->generate('numbernine_homepage')
            : $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    public function authenticate(Request $request): Passport
    {
        $username = (string) $request->request->get('username');
        $password = (string) $request->request->get('password');
        $csrfToken = (string) $request->request->get('_csrf_token');

        return new Passport(new UserBadge($username), new PasswordCredentials($password), [
            new RememberMeBadge(),
            new CsrfTokenBadge('authenticate', $csrfToken),
        ]);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('numbernine_login');
    }
}
