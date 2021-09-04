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

use NumberNine\Entity\ContentEntity;
use NumberNine\Content\PermalinkGenerator;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ContentEntityNormalizer implements NormalizerInterface
{
    public function __construct(private ObjectNormalizer $normalizer, private PermalinkGenerator $permalinkGenerator)
    {
    }

    /**
     * @param mixed $object
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        if (is_array($data) && array_key_exists('publicUrl', $data)) {
            $data['publicUrl'] = $this->permalinkGenerator->generateContentEntityPermalink($object, 1, true);
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof ContentEntity && $data->getId();
    }
}
