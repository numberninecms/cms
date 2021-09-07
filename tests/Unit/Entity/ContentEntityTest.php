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

namespace NumberNine\Tests\Unit\Entity;

use Doctrine\ORM\EntityManagerInterface;
use NumberNine\Entity\ContentEntity;
use NumberNine\Model\Content\PublishingStatusInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
final class ContentEntityTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testPublishingDateDoesNotChangeAfterPersist(): void
    {
        $entity = (new ContentEntity())
            ->setTitle('New content entity')
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('January 13, 2016'))
            ->setPublishedAt(new \DateTime('February 26, 2018'))
        ;

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        static::assertSame('2018-02-26', $entity->getPublishedAt()->format('Y-m-d'));
    }

    public function testPublishingDateIsSetAfterFirstPublish(): void
    {
        $entity = (new ContentEntity())
            ->setTitle('New content entity')
            ->setStatus(PublishingStatusInterface::STATUS_PUBLISH)
            ->setCreatedAt(new \DateTime('January 13, 2016'))
        ;

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        static::assertSame(date('Y-m-d'), $entity->getPublishedAt()->format('Y-m-d'));
    }

    public function testPublishingDateIsNotSetIfSavedAsDraft(): void
    {
        $entity = (new ContentEntity())
            ->setTitle('New content entity')
            ->setStatus(PublishingStatusInterface::STATUS_DRAFT)
            ->setCreatedAt(new \DateTime('January 13, 2016'))
        ;

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        static::assertNull($entity->getPublishedAt());
    }
}
