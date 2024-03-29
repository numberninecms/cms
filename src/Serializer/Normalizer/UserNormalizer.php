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
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class UserNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)]
        private NormalizerInterface $normalizer,
        private PostRepository $postRepository,
    ) {
    }

    /**
     * @param mixed $object
     *
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        if (
            \is_array($data)
            && \in_array('author_get', $context['groups'] ?? [], true)
        ) {
            $data['postsCount'] = $this->postRepository->count(['author' => $object->getId()]);
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof User && $data->getId();
    }
}
