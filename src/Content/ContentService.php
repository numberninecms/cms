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

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use LogicException;
use NumberNine\Annotation\FormType;
use NumberNine\Entity\ContentEntity;
use NumberNine\Entity\User;
use NumberNine\Event\MainLoopQueryEvent;
use NumberNine\Event\PaginatorEvent;
use NumberNine\Exception\ContentTypeNotFoundException;
use NumberNine\Exception\InvalidContentTypeException;
use NumberNine\Model\Content\ContentType;
use NumberNine\Model\Pagination\PaginationParameters;
use NumberNine\Repository\AbstractContentEntityRepository;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use function Symfony\Component\String\u;

final class ContentService
{
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;
    private Reader $annotationReader;
    private TokenStorageInterface $tokenStorage;
    private EventDispatcherInterface $eventDispatcher;
    private SluggerInterface $slugger;

    /** @var ContentType[] */
    private array $contentTypes = [];

    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        Reader $annotationReader,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        SluggerInterface $slugger
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->annotationReader = $annotationReader;
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->slugger = $slugger;
    }

    /**
     * @param string $contentType
     * @return ContentType
     * @throws ContentTypeNotFoundException
     */
    public function getContentType(string $contentType): ContentType
    {
        foreach ($this->contentTypes as $type) {
            if (
                $type->getName() === $contentType
                || u($type->getLabels()->getPluralName())->snake()->toString() === $contentType
                || $this->slugger->slug((string)$type->getLabels()->getPluralName())->toString() === $contentType
            ) {
                return $type;
            }
        }

        throw new ContentTypeNotFoundException($contentType);
    }

    /**
     * @return ContentType[]
     */
    public function getContentTypes(): array
    {
        return $this->contentTypes;
    }

    /**
     * @param array $contentTypes
     */
    public function setContentTypes(array $contentTypes): void
    {
        $this->contentTypes = $contentTypes;
    }

    /**
     * @param ContentType|string $contentType Either the ContentType object, or the content type name as registered
     * @param PaginationParameters $paginationParameters
     * @param Criteria|null $criteria
     * @return Paginator
     * @throws QueryException
     */
    public function getEntitiesOfType(
        $contentType,
        PaginationParameters $paginationParameters,
        Criteria $criteria = null
    ): Paginator {
        if (is_string($contentType)) {
            $contentType = $this->getContentType($contentType);
        }

        /** @var AbstractContentEntityRepository $repository */
        $repository = $this->entityManager->getRepository($contentType->getEntityClassName());
        $queryBuilder = $repository->getPaginatedCollectionQueryBuilder(
            $contentType->getName(),
            $paginationParameters,
            $criteria
        );

        /** @var MainLoopQueryEvent $mainLoopQueryEvent */
        $mainLoopQueryEvent = $this->eventDispatcher->dispatch(new MainLoopQueryEvent($queryBuilder, $contentType));
        $queryBuilder = $mainLoopQueryEvent->getQueryBuilder();

        $result = new Paginator($queryBuilder, true);

        /** @var PaginatorEvent $paginatorEvent */
        $paginatorEvent = $this->eventDispatcher->dispatch(new PaginatorEvent($result));

        return $paginatorEvent->getPaginator();
    }

    /**
     * Todo: Low priority. Make this method do what it's meant to do.
     * At the moment it queries only the first content type of the array.
     *
     * @param ContentType[] $contentTypes
     * @param PaginationParameters $paginationParameters
     * @param Criteria|null $criteria
     * @return Paginator
     */
    public function getEntitiesOfMultipleTypes(
        array $contentTypes,
        PaginationParameters $paginationParameters,
        Criteria $criteria = null
    ): Paginator {
        if (empty($contentTypes)) {
            throw new RuntimeException('No ContentType object in array.');
        }

        // Todo: make a customized Paginator which can handle multiple sub-entities for the same query
        return $this->getEntitiesOfType(current($contentTypes), $paginationParameters, $criteria);
    }

    /**
     * @param ContentType|string $contentType Either the ContentType object or the content type name as registered
     * @param int $id
     * @return ContentEntity
     */
    public function getEntityOfType($contentType, $id): object
    {
        if (is_string($contentType)) {
            $contentType = $this->getContentType($contentType);
        }

        return $this->entityManager->getRepository($contentType->getEntityClassName())->find($id);
    }

    /**
     * @param ContentType|string $contentType Either the ContentType object or the content type name as registered
     * @param array $criteria Associative array representing the fields to search
     * @return ContentEntity
     */
    public function getEntityOfTypeBy($contentType, array $criteria): object
    {
        if (is_string($contentType)) {
            $contentType = $this->getContentType($contentType);
        }

        return $this->entityManager->getRepository($contentType->getEntityClassName())->findOneBy($criteria);
    }

    /**
     * @param string|ContentType $contentType
     * @param array $ids
     * @throws ORMException
     */
    public function deleteEntitiesOfType($contentType, array $ids): void
    {
        if (is_string($contentType)) {
            $contentType = $this->getContentType($contentType);
        }

        /** @var AbstractContentEntityRepository $repository */
        $repository = $this->entityManager->getRepository($contentType->getEntityClassName());
        $repository->removeCollection($ids);
        $this->entityManager->flush();
    }

    /**
     * @param string|ContentType $contentType
     * @throws ORMException
     */
    public function deletePermanentlyAllEntitiesOfType($contentType): void
    {
        if (is_string($contentType)) {
            $contentType = $this->getContentType($contentType);
        }

        /** @var AbstractContentEntityRepository $repository */
        $repository = $this->entityManager->getRepository($contentType->getEntityClassName());
        $repository->hardDeleteAllDeleted($contentType->getName());
        $this->entityManager->flush();
    }

    /**
     * @param string|ContentType $contentType
     * @param array $ids
     */
    public function restoreEntitiesOfType($contentType, array $ids): void
    {
        if (is_string($contentType)) {
            $contentType = $this->getContentType($contentType);
        }

        /** @var AbstractContentEntityRepository $repository */
        $repository = $this->entityManager->getRepository($contentType->getEntityClassName());
        $repository->restoreCollection($ids);
        $this->entityManager->flush();
    }

    /**
     * @param ContentType $contentType
     * @param array $options
     * @return FormInterface
     */
    public function getFormTypeNewForType(ContentType $contentType, array $options = []): FormInterface
    {
        try {
            $entityReflection = new ReflectionClass($contentType->getEntityClassName());
        } catch (ReflectionException $e) {
            throw new InvalidContentTypeException($contentType);
        }

        /** @var FormType $formType */
        $formType = $this->annotationReader->getClassAnnotation($entityReflection, FormType::class);

        $this->validateFormTypeAnnotation($formType, $contentType);

        /** @var ContentEntity $entity */
        $entity = $entityReflection->newInstance();
        /** @var User $user */
        $user = $this->tokenStorage->getToken() !== null ? $this->tokenStorage->getToken()->getUser() : null;
        $entity->setAuthor($user);

        return $this->formFactory->create($formType->new, $entity, $options);
    }

    /**
     * @param ContentType $contentType
     * @param ContentEntity $entity
     * @param array $options
     * @return FormInterface
     */
    public function getFormTypeEditForType(
        ContentType $contentType,
        ContentEntity $entity,
        array $options = []
    ): FormInterface {
        try {
            $entityReflection = new ReflectionClass($contentType->getEntityClassName());
        } catch (ReflectionException $e) {
            throw new InvalidContentTypeException($contentType);
        }

        /** @var FormType $formType */
        $formType = $this->annotationReader->getClassAnnotation($entityReflection, FormType::class);

        $this->validateFormTypeAnnotation($formType, $contentType);

        return $this->formFactory->create($formType->edit, $entity, $options);
    }

    /**
     * @param FormType|null $formType
     * @param ContentType $contentType
     */
    private function validateFormTypeAnnotation(?FormType $formType, ContentType $contentType): void
    {
        if (!$formType) {
            throw new LogicException(
                sprintf('@FormType annotation missing on class "%s".', $contentType->getEntityClassName())
            );
        }

        try {
            new ReflectionClass($formType->new);
        } catch (ReflectionException $e) {
            throw new LogicException(sprintf(
                'Class %s doesn\'t exist in @FormType annotation with parameter "new" for class %s.',
                $formType->new,
                $contentType->getEntityClassName()
            ));
        }

        try {
            new ReflectionClass($formType->edit);
        } catch (ReflectionException $e) {
            throw new LogicException(sprintf(
                'Class %s doesn\'t exist in @FormType annotation with parameter "edit" for class %s.',
                $formType->edit,
                $contentType->getEntityClassName()
            ));
        }
    }
}
