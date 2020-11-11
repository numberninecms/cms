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

use NumberNine\Entity\User;
use NumberNine\Repository\PostRepository;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class UserNormalizer implements NormalizerInterface
{
    private ObjectNormalizer $normalizer;
    private PostRepository $postRepository;

    public function __construct(ObjectNormalizer $normalizer, PostRepository $postRepository)
    {
        $this->normalizer = $normalizer;
        $this->postRepository = $postRepository;
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     * @return array
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        if (is_array($data)) {
            $data['postsCount'] = $this->postRepository->count(['author' => $object->getId()]);
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof User && $data->getId();
    }
}
