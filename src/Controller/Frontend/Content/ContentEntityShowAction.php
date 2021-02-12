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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use NumberNine\Entity\ContentEntity;
use NumberNine\Event\ContentEntityShowForwardEvent;
use NumberNine\Event\CurrentContentEntityEvent;
use NumberNine\Event\TemplateToRenderEvent;
use NumberNine\Model\Content\ContentType;
use NumberNine\Repository\AbstractContentEntityRepository;
use NumberNine\Security\Capabilities;
use NumberNine\Content\PermalinkGenerator;
use NumberNine\Theme\TemplateResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
* The route of this action is dynamically generated
* @see RouteRegistrationEventSubscriber
*/
final class ContentEntityShowAction extends AbstractController
{
    public function __invoke(
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        TemplateResolver $templateResolver,
        PermalinkGenerator $permalinkGenerator
    ): Response {
        /** @var ContentType $contentType */
        $contentType = $request->get('_content_type');

        /** @var AbstractContentEntityRepository $repository */
        $repository = $entityManager->getRepository($contentType->getEntityClassName());

        /** @var ContentEntity $entity */
        $entity = null;

        if ($slug = $request->get('slug')) {
            $entity = $repository->findOneBy(['slug' => $slug]);
        } elseif ($id = $request->get('id')) {
            $entity = $repository->find($id);
        }

        if ($entity === null) {
            throw new EntityNotFoundException('Missing slug or ID.');
        }

        $this->validateUrl($request, $entity, $permalinkGenerator);

        $this->denyAccessUnlessGranted(Capabilities::READ, $entity);

        $eventDispatcher->dispatch(new CurrentContentEntityEvent($entity));

        /** @var ContentEntityShowForwardEvent $contentEntityShowForwardEvent */
        $contentEntityShowForwardEvent = $eventDispatcher->dispatch(
            new ContentEntityShowForwardEvent($request, $entity)
        );
        $forwardResponse = $contentEntityShowForwardEvent->getResponse();

        /** @var TemplateToRenderEvent $templateToRenderEvent */
        $templateToRenderEvent = $eventDispatcher->dispatch(
            new TemplateToRenderEvent($request, $entity, $templateResolver->resolveSingle($entity))
        );

        $template = $templateToRenderEvent->getTemplate();

        if (preg_match('@/([\w_-]+)/index\.@', $template->getTemplateName(), $matches)) {
            return $forwardResponse ?? $this->forward(ContentEntityIndexAction::class, [
                'type' => $matches[1],
                'template' => $template->getTemplateName(),
            ]);
        }

        return $forwardResponse ?? new Response($templateToRenderEvent->getTemplate()->render(['entity' => $entity]));
    }

    private function validateUrl(Request $request, ContentEntity $entity, PermalinkGenerator $permalinkGenerator): void
    {
        if (
            $request->getPathInfo() === $this->generateUrl('numbernine_homepage')
            || $request->getPathInfo() === $this->generateUrl('numbernine_homepage_page', [
                'page' => $request->get('page', 1)
            ])
        ) {
            return;
        }

        $url = $permalinkGenerator->generateContentEntityPermalink($entity, $request->get('page', 1));

        if ($url !== $request->getPathInfo()) {
            throw new NotFoundHttpException();
        }
    }
}
