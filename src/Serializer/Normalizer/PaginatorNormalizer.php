<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Serializer\Normalizer;

use Exception;
use NumberNine\Pagination\Paginator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class PaginatorNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)]
        private NormalizerInterface $normalizer,
        private iterable $normalizers,
    ) {
    }

    /**
     * @param Paginator $object
     * @param string    $format
     *
     * @throws Exception
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $collection = array_map(
            function ($item) use ($format, $context) {
                foreach ($this->normalizers as $normalizer) {
                    if ($normalizer->supportsNormalization($item, $format)) {
                        return $normalizer->normalize($item, $format, $context);
                    }
                }

                return $this->normalizer->normalize($item, $format, $context);
            },
            iterator_to_array($object->getIterator())
        );

        return [
            'collection' => $collection,
            'totalItems' => $object->getTotalItems(),
            'page' => [
                'totalPages' => ceil($object->getTotalItems() / $object->getItemsPerPage()),
                'current' => $object->getCurrentPage(),
                'first' => 1,
                'last' => $object->getLastPage(),
                'previous' => max(1, $object->getCurrentPage() - 1),
                'next' => min($object->getLastPage(), $object->getCurrentPage() + 1),
            ],
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Paginator;
    }
}
