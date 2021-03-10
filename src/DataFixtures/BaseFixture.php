<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use NumberNine\Content\ContentService;
use RuntimeException;

abstract class BaseFixture extends Fixture
{
    private ObjectManager $manager;
    private ContentService $contentService;
    private array $referencesIndex = [];

    /**
     * @param ContentService $contentService
     */
    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    abstract protected function loadData(ObjectManager $manager): void;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadData($manager);
    }

    protected function createMany(string $className, int $count, callable $factory, int $offset = 0): void
    {
        for ($i = $offset; $i < $offset + $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }

    protected function createManyContentEntity(string $type, int $count, callable $factory): void
    {
        $contentType = $this->contentService->getContentType($type);
        $className = $contentType->getEntityClassName();

        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $entity->setCustomType($contentType->getName());

            $factory($entity, $i);

            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_Type_#COUNT#
            $this->addReference($className . '_' . $contentType->getName() . '_' . $i, $entity);
        }
    }

    /**
     * @param string $className
     * @param array $excludedReferences
     * @return mixed
     */
    protected function getRandomReference(string $className, array $excludedReferences = [])
    {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className . '_') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$className])) {
            throw new RuntimeException(sprintf('Cannot find any references for class "%s"', $className));
        }

        $references = array_diff($this->referencesIndex[$className], $excludedReferences);

        $randomReferenceKey = $references[array_rand($references)];
        return $this->getReference($randomReferenceKey);
    }
}
