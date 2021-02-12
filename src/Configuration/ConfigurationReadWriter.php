<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Configuration;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use NumberNine\Entity\CoreOption;
use NumberNine\Repository\CoreOptionRepository;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class ConfigurationReadWriter
{
    private EntityManagerInterface $entityManager;
    private CoreOptionRepository $coreOptionRepository;
    private TagAwareCacheInterface $cache;
    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        CoreOptionRepository $coreOptionRepository,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
    ) {

        $this->entityManager = $entityManager;
        $this->coreOptionRepository = $coreOptionRepository;
        $this->cache = $cache;
        $this->serializer = $serializer;
    }

    /**
     * Gets an option value. If the value contains a JSON string, it will be returned as an array.
     * @param string $optionName
     * @param mixed $default
     * @return mixed|null
     * @throws InvalidArgumentException
     * @throws CacheException
     */
    public function read(string $optionName, $default = null)
    {
        return $this->cache->get(
            sprintf('coreoption_value_%s_default_%s', $optionName, md5(serialize($default))),
            function (ItemInterface $item) use ($optionName, $default) {
                $value = ($option = $this->coreOptionRepository->findOneBy(['name' => $optionName]))
                    ? ($option->getValue() ?? $default)
                    : $default;

                try {
                    $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                } catch (Exception $e) {
                    // this wasn't a json string
                }

                $item->tag($optionName);

                return $value;
            }
        );
    }

    /**
     * Reads multiple configuration settings in one SQL request.
     *
     * Example:
     *
     * - ['option1', 'option2'] (null default values)
     * - ['option1' => 'default_for_option1', 'option2' => 'default_for_option2']
     *
     * @param array $options Use numeric array for null default values, or associative for custom default values
     * @param bool $resultAsAssociativeArray
     * @return mixed
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    public function readMany(array $options, bool $resultAsAssociativeArray = true)
    {
        foreach ($options as $key => $value) {
            if (is_numeric($key)) {
                $options[$value] = null;
                unset($options[$key]);
            }
        }

        $defaults = $options;
        $optionNames = array_keys($options);

        return $this->cache->get(
            sprintf(
                'coreoption_batch_%s_assoc_%s',
                md5(serialize($options)),
                $resultAsAssociativeArray ? 'true' : 'false',
            ),
            function (ItemInterface $item) use ($resultAsAssociativeArray, $optionNames, $defaults) {
                $result = $this->coreOptionRepository->findBy(['name' => $optionNames]);
                $finalArray = [];

                foreach ($optionNames as $optionName) {
                    $coreOption = current(array_filter($result, fn(CoreOption $i) => $i->getName() === $optionName));
                    $default = array_key_exists($optionName, $defaults) ? $defaults[$optionName] : null;
                    $value = $coreOption && $coreOption->getValue() ? $coreOption->getValue() : $default;

                    try {
                        $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                    } catch (Exception $e) {
                        // this wasn't a json string
                    }

                    if ($resultAsAssociativeArray) {
                        $finalArray[$optionName] = $value;
                    } else {
                        $finalArray[] = [
                            'name' => $optionName,
                            'value' => $value
                        ];
                    }

                    $item->tag($optionName);
                }

                return $finalArray;
            }
        );
    }

    /**
     * @param string $optionName
     * @param mixed $value
     * @param bool $flush
     * @throws InvalidArgumentException
     */
    public function write(string $optionName, $value, bool $flush = true): void
    {
        $option = $this->coreOptionRepository->findOneBy(['name' => $optionName]);

        if (!$option) {
            $option = new CoreOption();
            $option->setName($optionName);
        }

        if (empty($value)) {
            $value = null;
        } else {
            $value = $this->serializer->serialize($value, 'json');
        }

        $option->setValue($value);
        $this->entityManager->persist($option);

        if ($flush) {
            $this->entityManager->flush();
        }

        $this->cache->invalidateTags([$optionName]);
        $this->saveCache($optionName, $value);
    }

    /**
     * @param array $optionNames
     * @throws InvalidArgumentException
     */
    public function writeMany(array $optionNames): void
    {
        foreach ($optionNames as $name => $value) {
            $this->write($name, $value, false);
        }

        $this->entityManager->flush();
    }

    /**
     * @param string $optionName
     * @param mixed $value
     */
    private function saveCache(string $optionName, $value): void
    {
        if (trim($optionName) === '') {
            return;
        }

        try {
            $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            // this wasn't a json string
        }

        $cacheItem = $this->cache
            ->getItem(sprintf('coreoption_value_%s', $optionName))
            ->set($value);

        $this->cache->save($cacheItem);
    }
}
