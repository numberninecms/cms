<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NumberNine\Controller\Admin;

use NumberNine\Content\ContentService;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\User;
use NumberNine\Security\Capabilities;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @property ContentService $contentService
 *
 * @method UserInterface getUser()
 * @method void          denyAccessUnlessGranted(string $attribute)
 */
trait CanEditPostsTrait
{
    #[Required]
    public ContentService $contentService;

    private function assertCanEditPosts(UserInterface $user, ContentEntity $entity): void
    {
        $contentType = $this->contentService->getContentType($entity->getType());
        $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::EDIT_POSTS));

        if (
            $user instanceof User
            && $entity->getAuthor() instanceof User
            && $user->getId() !== $entity->getAuthor()->getId()
        ) {
            $this->denyAccessUnlessGranted($contentType->getMappedCapability(Capabilities::EDIT_OTHERS_POSTS));
        }
    }
}
