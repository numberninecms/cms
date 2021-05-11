<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\EventSubscriber;

use NumberNine\Content\ContentService;
use NumberNine\Entity\ContentEntity;
use NumberNine\Event\CurrentContentEntityEvent;
use NumberNine\Asset\TagRenderer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ResponseEventSubscriber implements EventSubscriberInterface
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;
    private Environment $twig;
    private TagRenderer $tagRenderer;
    private bool $alreadyRendered = false;
    private ?Request $request;
    private ?ContentEntity $currentContentEntity = null;
    private ContentService $contentService;

    /**
     * @param RequestStack $requestStack
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     * @param Environment $twig
     * @param TagRenderer $tagRenderer
     */
    public function __construct(
        RequestStack $requestStack,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        Environment $twig,
        TagRenderer $tagRenderer,
        ContentService $contentService
    ) {
        $this->request = $requestStack->getMasterRequest();
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->twig = $twig;
        $this->tagRenderer = $tagRenderer;
        $this->contentService = $contentService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => 'renderAdminBar',
            CurrentContentEntityEvent::class => 'storeCurrentContentEntity',
        ];
    }

    /**
     * @param ResponseEvent $event
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderAdminBar(ResponseEvent $event): void
    {
        if (
            !(
            !$this->alreadyRendered
            && $this->tokenStorage->getToken()
            && $this->authorizationChecker->isGranted('Administrator')
            && $event->getResponse()->getStatusCode() === 200
            )
        ) {
            return;
        }

        $response = $event->getResponse();
        $navtop = $this->twig->render(
            '@NumberNine/partials/navtop.html.twig',
            [
                'entity' => $this->currentContentEntity,
                'content_type' => $this->currentContentEntity
                    ? $this->contentService->getContentType($this->currentContentEntity->getType())
                    : null,
            ]
        );
        $navtopScript = '';

        if (
            $this->request
            && (
                strpos($this->request->attributes->get('_route'), 'numbernine_admin_') === 0
                || $this->request->get('n9') === 'admin'
            )
        ) {
            $navtopStyles = ''; // $this->tagRenderer->renderWebpackLinkTags('adminpreviewmode', 'numbernine');
        } else {
            $navtopStyles = $this->tagRenderer->renderWebpackLinkTags('adminbar', 'numbernine');
            $navtopScript = $this->tagRenderer->renderWebpackScriptTags('adminbar', 'numbernine', true);
            $response->setContent(preg_replace('@(<body.*>)@simU', '$1' . $navtop, (string)$response->getContent()));
        }

        $response->setContent(preg_replace(
            '@</head>@i',
            $navtopStyles . $navtopScript . '</head>',
            (string)$response->getContent()
        ));

        $this->alreadyRendered = true;
    }

    public function storeCurrentContentEntity(CurrentContentEntityEvent $event): void
    {
        $this->currentContentEntity = $event->getContentEntity();
    }
}
