<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Http;

use ArrayIterator;
use Doctrine\ORM\Tools\Pagination\Paginator;
use NumberNine\Annotation\ExtendedReader;
use NumberNine\Attribute\NormalizationContext;
use NumberNine\Content\ContentService;
use NumberNine\Entity\ContentEntity;
use NumberNine\Pagination\Paginator as NumberNinePaginator;
use ReflectionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ResponseFactory
{
    public function __construct(private SerializerInterface $serializer, private ExtendedReader $annotationReader, private ContentService $contentService, private iterable $normalizers)
    {
    }

    /**
     * @param mixed|null $data
     *
     * @throws ExceptionInterface
     */
    public function createSerializedJsonResponse(
        $data = null,
        array $context = [],
        int $status = 200,
        array $headers = []
    ): JsonResponse {
        if ($data instanceof ContentEntity) {
            $type = $this->contentService->getContentType((string) $data->getCustomType());

            if (empty($context)) {
                $normalizationContext = $this->annotationReader->getFirstAnnotationOrAttributeOfType(
                    $type->getEntityClassName(),
                    NormalizationContext::class
                ) ?? [];
                $context = array_merge_recursive($context, (array) $normalizationContext);
            }

            $data = $this->normalize($data, null, $context);
        }

        return new JsonResponse($this->serializer->serialize($data, 'json', $context), $status, $headers, true);
    }

    /**
     * Creates a json response with paginated data.
     *
     * @throws ReflectionException
     */
    public function createSerializedPaginatedJsonResponse(
        Paginator $data,
        array $context = [],
        int $status = 200,
        array $headers = []
    ): JsonResponse {
        /** @var ArrayIterator $iterator */
        $iterator = $data->getIterator();

        if (empty($context) && $iterator->count() > 0) {
            $normalizationContext = $this->annotationReader->getFirstAnnotationOrAttributeOfType(
                $iterator->current()::class,
                NormalizationContext::class
            ) ?? [];
            $context = array_merge_recursive($context, (array) $normalizationContext);
        }

        $paginator = new NumberNinePaginator($data);
        $normalizedData = $this->normalize($paginator, null, $context);

        return new JsonResponse(
            $this->serializer->serialize($normalizedData, 'json', $context),
            $status,
            $headers,
            true
        );
    }

    public function createSuccessJsonResponse(string $message = '', int $status = 200): JsonResponse
    {
        return new JsonResponse($message ? ['success' => $message] : [], $status);
    }

    public function createErrorJsonResponse(string $message = '', int $status = 400): JsonResponse
    {
        return new JsonResponse(['error' => $message ?: 'Unexpected error'], $status);
    }

    /**
     * @param mixed $data
     *
     * @throws ExceptionInterface
     *
     * @return mixed
     */
    private function normalize($data, string $format = null, array $context = [])
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($data, $format)) {
                return $normalizer->normalize($data, $format, $context);
            }
        }

        return $data;
    }
}
