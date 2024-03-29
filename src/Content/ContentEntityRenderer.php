<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Content;

use Doctrine\ORM\EntityNotFoundException;
use NumberNine\Entity\ContentEntity;
use NumberNine\Repository\ContentEntityRepository;
use NumberNine\Theme\TemplateResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ContentEntityRenderer
{
    public function __construct(private ContentEntityRepository $contentEntityRepository, private AuthorizationCheckerInterface $authorizationChecker, private Environment $twig, private TemplateResolver $templateResolver)
    {
    }

    /**
     * @throws EntityNotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderBySlug(string $slug, bool $throwException = true): string
    {
        $entity = $this->contentEntityRepository->findOneBy(['slug' => $slug]);

        if (!$entity) {
            if ($throwException) {
                throw new EntityNotFoundException(
                    sprintf("Entity of type '%s' for slug '%s' was not found", ContentEntity::class, $slug)
                );
            }

            if ($this->authorizationChecker->isGranted('Administrator')) {
                return $this->twig->render('@NumberNine/alerts/entity_missing.html.twig', ['slug' => $slug]);
            }

            return '';
        }

        return $this->render($entity);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(ContentEntity $entity): string
    {
        return $this->templateResolver->resolveSingle($entity)->render(['entity' => $entity]);
    }
}
